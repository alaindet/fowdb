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

        // attribute = 0 specifically selected (Void icon)
        if (in_array('no', $values)) {
            $filters[] = 'attribute_bit = 0';
            return;
        }

        // ONLY SELECTED attributes
        if ($flags['attribute_selected']) {
            $bitval = bindec(decbin(~$bitmask->getMask())); // Flip mask
            $filters[] = "attribute_bit & {$bitval} = 0";
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
