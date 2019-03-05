<?php

namespace App\Services\Resources\Card\Search;

use App\Base\Search\SearchInterface;
use App\Models\CardType;

/**
 * From App\Services\Resources\Card\Search\Search
 * ==============================================
 * protected $statement;
 * protected $bind;
 * protected $state;
 * 
 * List of all possible state variables
 * ====================================
 * atk
 * atk-operator
 * attributes
 * attributes-only-multi
 * attributes-only-single
 * attributes-selected
 * cost
 * cost-x
 * def
 * def-operator
 * query
 * query-exact
 * query-fields
 * sort
 * sort-direction
 * types
 * types-selected
 */
trait ParameterProcessorsTrait
{
    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: query
     *
     * @param string $value
     * @return void
     */
    protected function processQueryParameter(string $value): void
    {
        $this->state['query'] = $value;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: query-exact
     *
     * @param string $value
     * @return void
     */
    protected function processPartialMatchParameter(string $value): void
    {
        $this->state['query-exact'] = false;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: query-fields
     *
     * @param array $value
     * @return void
     */
    protected function processInFieldsParameter(string $value): void
    {
        $this->state['query-fields'] = $value;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: sort
     *
     * @param string $value
     * @return void
     */
    protected function processSortParameter(string $value): void
    {
        if ($value === 'no') return;

        $this->state['sort'] = $value;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: sort-direction
     *
     * @param string $value
     * @return void
     */
    protected function processSortDirectionParameter(string $value): void
    {
        $this->state['sort-direction'] = $value;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: attributes
     *
     * @param array $values
     * @return void
     */
    protected function processAttributeParameter(array $values): void
    {
        $this->state['attributes'] = $values;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: attributes-selected
     *
     * @param string $value
     * @return void
     */
    protected function processSelectedAttributeParameter(string $value): void
    {
        $this->state['attributes-selected'] = true;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: attributes-only-single
     *
     * @param string $value
     * @return void
     */
    protected function processSingleAttributeParameter(string $value): void
    {
        $this->state['attributes-only-single'] = true;
    }

    /**
     * Sets a state variable for later use (multi-input processor)
     * 
     * State: attributes-only-multi
     *
     * @param string $value
     * @return void
     */
    protected function processMultiAttributeParameter(string $value): void
    {
        $this->state['attributes-only-multi'] = true;
    }

    /**
     * Sets a state variable instead of adding a filter
     * 
     * State: atk-operator
     *
     * @param string $value
     * @return void
     */
    protected function processAtkOperatorParameter(string $value): void
    {
        $this->state['atk-operator'] = $value;
    }

    /**
     * Sets a state variable instead of adding a filter
     * 
     * State: atk
     *
     * @param string $value
     * @return void
     */
    protected function processAtkValueParameter(string $value): void
    {
        $this->state['atk'] = $value;
    }

    /**
     * Sets a state variable instead of adding a filter
     * 
     * State: def-operator
     *
     * @param string $value
     * @return void
     */
    protected function processDefOperatorParameter(string $value): void
    {
        $this->state['def-operator'] = $value;
    }

    /**
     * Sets a state variable instead of adding a filter
     * 
     * State: def
     *
     * @param string $value
     * @return void
     */
    protected function processDefValueParameter(string $value): void
    {
        $this->state['def'] = $value;
    }

    /**
     * Sets a state variable instead of adding a filter
     * 
     * State: types
     *
     * @param string $values
     * @return void
     */
    protected function processTypeParameter(array $values): void
    {
        $this->state['types'] = $values;
    }

    /**
     * Sets a state variable instead of adding a filter
     * 
     * State: types-selected
     *
     * @param string $values
     * @return void
     */
    protected function processTypeSelectedParameter(string $value): void
    {
        $this->state['types-selected'] = true;
    }

    protected function processExcludeParameter(array $values): void
    {
        foreach ($values as $toExclude) {
            switch ($toExclude) {
                case 'spoilers':
                    $list = implode(',', $this->lookup->get('spoilers.ids'));
                    $this->statement->where("NOT(sets_id IN({$list}))");
                    break;
                case 'basics':
                    $this->statement->where('narp <> 0');
                    break;
                case 'alternates':
                    $this->statement->where('narp <> 1');
                    break;
                case 'reprints':
                    $this->statement->where('narp <> 2');
                    break;
            }
        }
    }

    protected function processFormatParameter(string $value): void
    {
        $clusters = $this->lookup->get("formats.code2clusters.{$value}");

        // ERROR: Wrong format
        if (!isset($clusters)) return;

        $clustersList = implode(',', $clusters);
        $this->statement->where("clusters_id IN ({$clustersList})");
    }

    protected function processBackSideParameter(array $values): void
    {
        $where = [];
        $baseKey = ':backside';
        $keyCounter = 1;
        $map = $this->lookup->get('backsides.code2id');

        for ($i = 0, $ii = count($values); $i < $ii; $i++) {
            $temp = $map[$values[$i]] ?? null;
            if (!isset($temp)) continue;
            $key = $baseKey . $keyCounter++;
            $this->bind[$key] = $temp;
            $where[] = "back_side = {$key}";
        }

        $this->statement->where($where, 'OR');
    }

    protected function processDivinityParameter(array $values): void
    {
        $where = [];
        $baseKey = ':divinity';
        $keyCounter = 1;

        for ($i = 0, $ii = count($values); $i < $ii; $i++) {
            $key = $baseKey . $keyCounter++;
            $this->bind[$key] = $values[$i];
            $where[] = "divinity = {$key}";
        }

        $this->statement->where($where, 'OR');
    }

    /**
     * Allows both array and string as input
     *
     * @param string|string[] $value
     * @return void
     */
    protected function processSetParameter($value): void
    {
        $map = $this->lookup->get('sets.code2id');
        
        // Single set
        if (is_string($value)) {
            if ($value === 'no' || $value === '0') return;
            $this->statement->where('sets_id = :setid');
            $this->bind[':setid'] = $map[$value];
            return;
        }

        // Multiple sets
        for ($i = 0, $ii = count($value); $i < $ii; $i++) {
            $validValue = $map[$value[$i]] ?? null;
            if (!isset($validValue)) unset($value[$i]);
            else $value[$i] = $validValue;
        }

        $values = implode(',', $value);
        $this->statement->where("sets_id IN ({$values})");
    }

    /**
     * Allows both array and string as input
     *
     * @param string|string[] $value
     * @return void
     */
    protected function processClusterParameter($value): void
    {
        $map = $this->lookup->get('clusters.code2id');
        
        // Single cluster
        if (is_string($value)) {
            if ($value === 'no' || $value === '0') return;
            $this->statement->where('clusters_id = :clusterid');
            $this->bind[':clusterid'] = $map[$value];
            return;
        }

        // Multiple clusters
        for ($i = 0, $ii = count($value); $i < $ii; $i++) {
            $validValue = $map[$value[$i]] ?? null;
            if (!isset($validValue)) unset($value[$i]);
            else $value[$i] = $validValue;
        }
        $values = implode(',', $value);
        $this->statement->where("clusters_id IN ({$values})");
    }

    protected function processCostParameter(array $values): void
    {
        $where = [];
        $baseKey = ':cost';
        $keyCounter = 1;

        for ($i = 0, $ii = count($values); $i < $ii; $i++) {
            $key = $baseKey . $keyCounter++;
            $this->bind[$key] = $values[$i];
            $where[] = "total_cost = {$key}";
        }

        $this->statement->where($where, 'OR');
    }

    protected function processFreeCostParameter(array $values): void
    {
        $where = [];
        $baseKey = ':freecost';
        $keyCounter = 1;

        for ($i = 0, $ii = count($values); $i < $ii; $i++) {
            $key = $baseKey . $keyCounter++;
            $this->bind[$key] = $values[$i];
            $where[] = "free_cost = {$key}";
        }

        $this->statement->where($where, 'OR');
    }

    protected function processCostXParameter(string $value): void
    {
        $this->statement->where('free_cost < 0');
    }

    protected function processRarityParameter(array $values): void
    {
        $where = [];
        $baseKey = ':rarity';
        $keyCounter = 1;

        for ($i = 0, $ii = count($values); $i < $ii; $i++) {
            $key = $baseKey . $keyCounter++;
            $this->bind[$key] = $values[$i];
            $where[] = "rarity = {$key}";
        }

        $this->statement->where($where, 'OR');
    }

    protected function processRaceParameter(string $value): void
    {
        $this->statement->where([
            'race LIKE :race_beginning',
            'race = :race_exact',
            'race LIKE :race_middle',
            'race LIKE :race_end'
        ], 'OR');

        $this->bind[':race_beginning'] = "{$value}/%";
        $this->bind[':race_exact'] = $value;
        $this->bind[':race_middle'] = "%/{$value}/%";
        $this->bind[':race_end'] = "%/{$value}";
    }

    protected function processIllustratorParameter(string $value): void
    {
        $this->statement->where('artist_name = :artist');
        $this->bind[':artist'] = $value;
    }
}
