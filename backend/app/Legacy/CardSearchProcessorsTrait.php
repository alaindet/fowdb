<?php

namespace App\Legacy;

use App\Utils\BitmaskFlags;

trait CardSearchProcessorsTrait
{
    private function processAttributeInput(
        array $values,
        array $flags,
        array &$filters
    ): void
    {
        $map = lookup('attributes.code2bit');
        $bitmask = new BitmaskFlags;
        $bitmask->setFlagsMap($map);
        $bitmask->addFlags($values);

        // Any combination of selected attributes containing "attribute[]=no"
        // Will return ONLY attribute-less cards
        if (in_array("no", $values)) {
            $filters[] = "attribute_bit = 0";
            return;
        }
        
        // Unless specifically requested (e.g. attribute[]=no)
        // Any selected attribute IMPLIES cards with attribute_bit > 0
        else {
            $filters[] = "attribute_bit > 0";
        }

        // ONLY SELECTED attributes
        if ($flags['attribute_selected']) {

            // Flipped mask
            $totalAttributes = 5;
            $mask = $bitmask->getMask();
            $flippedMaskRaw = decbin(~$mask); // string
            $flippedMaskTrimmed = substr($flippedMaskRaw, -$totalAttributes);
            $flippedMask = bindec($flippedMaskTrimmed);

            // Any card with any attribute more than the selected ones
            // Will have attribute_bit & flipped > 0
            $filters[] = "attribute_bit & {$flippedMask} = 0";
        }

        // AT LEAST ONE (default)
        else {
            $bitval = $bitmask->getMask();
            $filters[] = "attribute_bit & {$bitval} > 0";
        }

        // ONLY MULTI-ATTRIBUTE
        if ($flags['attribute_multi']) {
            $bitvals = [0];
            foreach ($map as $code => $bitpos) {
                $bitvals[] = $bitmask->getBitValue($bitpos);
            }
            $attrString = implode(',', $bitvals);
            $filters[] = "NOT(attribute_bit IN ({$attrString}))";
        }

        // NO SINGLE-ATTRIBUTE
        if ($flags['no_attribute_multi']) {
            $bitvals = [0];
            foreach ($map as $code => $bitpos) {
                $bitvals[] = $bitmask->getBitValue($bitpos);
            }
            $attrString = implode(',', $bitvals);
            $filters[] = "attribute_bit IN ({$attrString})";
        }
    }
}
