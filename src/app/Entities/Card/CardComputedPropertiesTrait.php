<?php

namespace App\Entities\Card;

use App\Utils\Bitmask;
use App\Utils\BitmaskFlags;
use App\Views\Card\CardText;

/**
 * This trait provides all property accessors for
 * "html-*" properties of App\Entities\Card\Card
 */
trait CardComputedPropertiesTrait
{
    protected function getTypeNamesProperty(): array
    {
        $types = [];
        $bitmask = (new Bitmask)->setMask($this->type_bit);
        $map = lookup('types.display');
        foreach ($map as $type => $bitvalue) {
            if ($bitmask->hasBitValue($bitvalue)) {
                $types[] = $type;
            }
        }
        return $types;
    }

    protected function getAttributeCodesProperty(): array
    {
        return (new BitmaskFlags)
            ->setMask($this->attribute_bit)
            ->setFlagsMap(lookup('attributes.code2bit'))
            ->readFlags();
    }

    protected function getAttributeNamesProperty(): array
    {
        return (new BitmaskFlags)
            ->setMask($this->attribute_bit)
            ->setFlagsMap(lookup('attributes.name2bit'))
            ->readFlags();
    }

    protected function getFormatsProperty(): array
    {
        
    }

    protected function getHtmlNameProperty(): array
    {
        return ['Name', $this->name];
    }

    protected function getHtmlTypeProperty(): array
    {
        $htmlBits = [];
        foreach ($this->type_names as $type) {
            $link = url('cards', ['type' => [$type]]);
            $htmlBit = "<a href=\"{$link}\">{$type}</a>";
            $htmlBits[] = $htmlBit;
        }
        $html = implode(' / ', $htmlBits);

        if ($this->back_side === '2') {
            $html .= ' (Shift)';
        }

        return ['Type', $html];
    }

    protected function getHtmlCostProperty(): ?array
    {
        if ($this->free_cost === null && $this->attribute_cost === null) {
            return null;
        }

        // Free cost
        $freeCostHtml = '';
        if ($this->free_cost !== null && $this->free_cost !== '0') {
            $freeCost = intval($this->free_cost);
            if ($freeCost > 0) {
                $freeCostHtml = '{'.$this->free_cost.'}';
            } else {
                $freeCostHtml = '';
                for ($i = $freeCost; $i < 0; $i++) {
                    $freeCostHtml .= '{x}';
                }
            }
            $freeCostHtml = CardText::render($freeCostHtml);
        }

        // Attribute cost
        $attrCostHtml = '';
        if ($this->attribute_cost !== null && $this->attribute_cost !== '0') {
            foreach (str_split($this->attribute_cost) as $attribute) {
                $attrCostHtml .= '{'.$attribute.'}';
            }
            $attrCostHtml = CardText::render($attrCostHtml);
        }

        // Final cost
        $html = $attrCostHtml . $freeCostHtml;

        return ['Cost', $html];
    }

    protected function getHtmlTotalCostProperty(): ?array
    {
        if ($this->free_cost === null && $this->attribute_cost === null) {
            return null;
        }

        $link = url('cards', ['total_cost' => [$this->total_cost]]);
        $html = "<a href=\"{$link}\">{$this->total_cost}</a>";

        return ['Total Cost', $html];
    }

    protected function getHtmlBattleValuesProperty(): ?array
    {
        // No battling type (ex.: Chant)
        if ($this->atk === null && $this->def === null) {
            return null;
        }

        // No battle values *BUT* battling type (ex.: Reflect)
        if ($this->atk === '0' && $this->def === '0') {
            return ['ATK / DEF', (
                '<em>(No battle values)</em>'
            )];
        }

        // Has battle values
        return ['ATK / DEF', (
            '<span class="font-150 text-italic">'.
                "{$this->atk} / {$this->def}".
            '</span>'
        )];
    }

    protected function getHtmlDivinityProperty(): ?array
    {
        if ($this->divinity === null) {
            return null;
        }

        $link = url('cards', ['divinity' => [$this->divinity]]);
        $html = "<a href=\"{$link}\">{$this->divinity}</a>";

        return ['Divinity', $html];
    }

    /**
     * Requires 'type_bit' property
     *
     * @return array|null
     */
    protected function getHtmlRaceProperty(): ?array
    {
        if ($this->race === null) {
            return null;
        }

        $label = 'Race';
        $raceTypes = ['Ruler', 'J-Ruler', 'Resonator']; // TODO

        // Retrieve from temporary cache, if present
        if (array_diff($raceTypes, $this->type_names) === $raceTypes) {
            $label = 'Trait';
        }

        $htmlBits = [];
        $races = explode('/', $this->race);
        foreach ($races as $race) {
            $link = url('cards', ['race' => $race]);
            $htmlBits[] = "<a href=\"{$link}\">{$race}</a>";
        }
        $html = implode(' / ', $htmlBits);

        return [$label, $html];
    }

    protected function getHtmlAttributeProperty(): ?array
    {
        if ($this->attribute_bit === '0') {
            return null;
        }

        $names = $this->attribute_names;
        $codes = $this->attribute_codes;

        $htmlBits = [];
        for ($i = 0, $len = count($names); $i < $len; $i++) {
            $name = &$names[$i];
            $code = &$codes[$i];
            $link = url('cards', ['attribute' => [$code]]);
            $blankImage = asset('images/icons/blank.gif');
            $htmlBits[] = (
                "<a href=\"{$link}\">".
                    "<img src=\"{$blankImage}\". class=\"fd-icon-{$code}\">".
                    "&nbsp;{$name}".
                "</a>"
            );
        }
        $html = implode(', ', $htmlBits);

        return ['Attribute', $html];
    }

    protected function getHtmlTextProperty(): ?array
    {
        if ($this->text === null) {
            return null;
        }

        $html = CardText::render($this->text);

        return ['Text', $html];
    }

    protected function getHtmlFlavorTextProperty(): ?array
    {
        if ($this->flavor_text === null) {
            return null;
        }

        $html = "<span class=\"text-italic\">{$this->flavor_text}</span>";

        return ['Flavor Text', $html];
    }

    protected function getHtmlCodeProperty(): array
    {
        $html = $this->code;

        return ['Code', $html];
    }

    protected function getHtmlRarityProperty(): ?array
    {
        if ($this->rarity === null) {
            return null;
        }

        $name = lookup("rarities.code2name.{$this->rarity}");
        $label = strtoupper($this->rarity).' - '.$name;
        $link = url('cards', ['rarity' => [$this->rarity]]);
        $html = "<a href=\"{$link}\">{$label}</a>";

        return ['Rarity', $html];
    }

    protected function getHtmlArtistProperty(): ?array
    {
        if ($this->artist_name === null) {
            return null;
        }

        $link = url('cards', ['artist' => $this->artist_name]);
        $html = "<a href=\"{$link}\">{$this->artist_name}</a>";

        return ['Artist', $html];
    }

    protected function getHtmlSetProperty(): array
    {
        $id = $this->sets_id;
        $code = lookup("sets.id2code.{$id}");
        $name = lookup("sets.id2name.{$id}");
        $label = strtoupper($code).' - '.$name;
        $link = url('cards', ['set' => $code]);
        $html = "<a href=\"{$link}\">{$label}</a>";

        return ['Set', $html];
    }

    protected function getHtmlClusterProperty(): array
    {
        $id = $this->clusters_id;
        $code = lookup("clusters.id2code.{$id}");
        $name = lookup("clusters.id2name.{$id}");
        $link = url('cards', ['cluster' => $code]);
        $html = "<a href=\"{$link}\">{$name}</a>";

        return ['Cluster', $html];
    }

    protected function getHtmlFormatProperty(): array
    {
        // Spoiler card (no format yet)
        $spoilers = lookup('spoilers.ids');
        if (!empty($spoilers) && in_array($this->sets_id, $spoilers)) {
            return ['Format', '<span class="fd-mark-spoiler">Spoiler</span>'];
        }



        $html = '';

        return ['Format', $html];
    }

    protected function getHtmlBannedProperty(): ?array
    {
        return ['Banned', 'html-banned'];
    }

    protected function getHtmlImageProperty(): array
    {
        return ['Image', 'html-image'];
    }
}
