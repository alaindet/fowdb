<?php

namespace App\Legacy\Card;

trait CardPropertiesTrait
{
    protected static function buildTypeProperty(
        array $labels,
        bool $isShift = false
    ): string
    {
        $htmlBits = [];

        // Build HTML
        foreach ($labels as $label) {
            $link = fd_url('cards', ['type' => [$label]]);
            $htmlBits[] = "<a href=\"{$link}\">{$label}</a>";
        }
        $html = implode(' / ', $htmlBits);

        // Add ' (Shift)' to shift cards
        if ($isShift) {
            $html .= ' (Shift)';
        }

        return $html;
    }

    protected static function buildCostProperty(array &$card): string
    {
        return '';
    }
}
