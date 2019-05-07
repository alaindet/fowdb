<?php

namespace App\Services\Resources\Card\Search;

use App\Base\Search\SearchInterface;
use App\Models\CardType;
use App\Services\Resources\Card\Search\SearchBarProcessorTrait;
use App\Utils\BitmaskFlags;
use App\Utils\Bitmask;
use App\Utils\Arrays;

/**
 * From App\Services\Resources\Card\Search\Search
 * 
 * protected $statement;
 * protected $bind;
 * protected $state;
 * protected $lookup
 */
trait AfterProcessingTrait
{
    use SearchBarProcessorTrait;

    protected function afterProcessing(): SearchInterface
    {
        $this->postProcessQuery();
        $this->postProcessAttributes();
        $this->postProcessTypes();
        $this->postProcessAtk();
        $this->postProcessDef();
        $this->postProcessSort();
        return $this;
    }

    private function postProcessQuery(): void
    {
        if ($this->state['query'] === '') return;

        $query = &$this->state['query'];

        $query = str_replace(['&#039;','&quot;'], ['\'', "\""], $query);

        $query = $this->queryPreserveWhitespace($query);

        $fields = $this->querySelectFields($this->state['query-fields']);

        $where = [];

        // All search terms must match (default)
        // Ex.: "name LIKE '%foo%' AND code LIKE '%foo%'"
        if ($this->state['query-exact']) {
            foreach ($fields as $field) {
                $where[] = "{$field} LIKE :query";
            }
            $this->bind[':query'] = "%{$query}%";
        }
        
        // Allow partial matches
        else {
            $baseKey = ':q';
            $keyCounter = 1;
            $format = [];
            $terms = explode('%', $query);

            // Build format string for sprintf() and bind search terms
            for ($i = 0, $ii = count($terms); $i < $ii; $i++) {
                $key = $baseKey . ($keyCounter++);
                $format[] = '%1$s LIKE '.$key;
                $this->bind[$key] = '%'.$terms[$i].'%';
            }
            $formatString = implode(' OR ', $format);

            // Change placeholder with each field
            for ($i = 0, $ii = count($fields); $i < $ii; $i++) {
                $where[] = sprintf($formatString, $fields[$i]);
            }
        }

        // Add built filter to WHERE clause
        $this->statement->where($where, 'OR');
    }

    private function postProcessAttributes(): void
    {
        // ERROR: No attributes selected
        if (empty($this->state['attributes'])) return;

        // Whitelist the values
        $whitelist = array_keys($this->lookup->get('attributes.display'));
        $attributes = array_intersect($whitelist, $this->state['attributes']);

        // ERROR: No *VALID* attributes
        if (empty($attributes)) return;

        // attribute = 0 specifically selected (Void icon)
        if (in_array('no', $attributes)) {
            $this->statement->where('attribute_bit = 0');
            return;
        }

        // Unless specifically selected, when selecting any attribute filter
        // All attribute-less cards are excluded
        else {
            $this->statement->where('attribute_bit > 0');
        }

        $map = $this->lookup->get('attributes.code2bit');
        $bitmask = (new BitmaskFlags)
            ->setFlagsMap($map)
            ->addFlags($attributes);

        // Match cards with ONLY THE SELECTED attributes
        if ($this->state['attributes-only-selected']) {
            $bitval = bindec(decbin($bitmask->getFlippedMask())); // Flip mask
            $this->statement->where("attribute_bit & {$bitval} = 0");
        }

        // Match cards with AT LEAST ONE of the selected attributes (default)
        else {
            $bitval = $bitmask->getMask();
            $this->statement->where("attribute_bit & {$bitval} > 0");
        }

        // Match ONLY MULTI-ATTRIBUTE cards
        if ($this->state['attributes-only-multi']) {
            $bitvals = [0]; // Avoids cards with no attribute
            foreach ($map as $code => $bitpos) {
                $bitvals[] = $bitmask->getBitValue($bitpos);
            }
            $attrString = implode(',', $bitvals);
            $this->statement->where("NOT(attribute_bit IN ({$attrString}))");
        }

        // Match ONLY SINGLE-ATTRIBUTE cards
        if ($this->state['attributes-only-single']) {
            $bitvals = [0]; // Avoids cards with no attribute
            foreach ($map as $code => $bitpos) {
                $bitvals[] = $bitmask->getBitValue($bitpos);
            }
            $attrString = implode(',', $bitvals);
            $this->statement->where("attribute_bit IN ({$attrString})");
        }
    }

    private function postProcessTypes(): void
    {
        // ERROR: No types selected
        if (empty($this->state['types'])) return;

        // Read the map (display_name => bit_value)
        $map = $this->lookup->get('types.display');
        $allowedTypeNames = array_keys($map);
    
        // Filter the types with the whitelist
        $types = Arrays::whitelist($this->state['types'], $allowedTypeNames);

        // ERROR: No *VALID* types
        if (empty($types)) return;

        // Match cards with ALL selected types
        if ($this->state['types-selected']) {

            // Assemble a composite bit flag with all card types
            $bitvals = 0;
            for ($i = 0, $ii = count($types); $i < $ii; $i++) {
                $bitvals |= $map[$types[$i]];
            }

            // Match the composite bit flag against the database
            $this->statement->where("type_bit & {$bitvals} = {$bitvals}");
            return;
        }

        // Match cards with AT LEAST ONE selected type
        $clauses = [];
        for ($i = 0, $ii = count($types); $i < $ii; $i++) {
            $type = &$types[$i];
            $bitval = $map[$type];
            $clauses[] = "type_bit & {$bitval} = {$bitval}";
        }

        $this->statement->where($clauses, 'or', 'and');
    }

    private function postProcessAtk(): void
    {
        if (!isset($this->state['atk'])) return;

        $map = [
            'lessthan' => '<',
            'equals' => '=',
            'morethan' => '>'
        ];

        $operator = $map[$this->state['atk-operator']] ?? '=';
        $this->statement->where("atk {$operator} :atk");
        $this->bind[':atk'] = $this->state['atk'];
    }

    private function postProcessDef(): void
    {
        if (!isset($this->state['def'])) return;

        $map = [
            'lessthan' => '<',
            'equals' => '=',
            'morethan' => '>'
        ];

        $operator = $map[$this->state['def-operator']] ?? '=';
        $this->statement->where("def {$operator} :def");
        $this->bind[':def'] = $this->state['def'];
    }

    private function postProcessSort(): void
    {
        // ERROR: No sorting field passed
        if (!isset($this->state['sort'])) {
            $this->statement->orderBy($this->state['sort-default']);
            return;
        }

        // code => name (Ex.: total_cost => Total Cost)
        $map = $this->lookup->get('sortables.cards');
        $whitelist = array_keys($map);

        // ERROR: No *VALID* sort field passed
        if (!in_array($this->state['sort'], $whitelist)) {
            $this->statement->orderBy($this->state['sort-default']);
            return;
        }

        $field = &$this->state['sort'];
        $dir = &$this->state['sort-direction'];

        // Particular fields
        switch ($field) {
            case 'attribute':
                $field = 'attribute_bit';
                $sort = "{$field} {$dir}";
                break;
            case 'rarity':
                $values = array_keys($this->lookup->get('rarities.code2id'));
                $list = implode("','", $values);
                $sort = "FIELD({$field},'{$list}') {$dir}";
                break;
            case 'type':
                $field = 'type_bit';
                $sort = "{$field} {$dir}";
                break;
            default:
                $sort = "{$field} {$dir}";
                break;
        }

        $this->statement->orderBy("{$sort}, {$this->state['sort-default']}");
    }
}
