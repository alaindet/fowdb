<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;

use App\Utils\Bitmask;

class BitmaskController extends Controller
{
    public function flipped(Request $request): string
    {
        $bitmask = new Bitmask();
        $bitmask
            ->addBits([0,1,2,3,5]);

        $formatter = function (Bitmask $bitmask, int $val) {
            $bin = decbin($val);
            $max = $bitmask->getCurrentMaxBits();
            return str_pad($bin, $max, "0", STR_PAD_LEFT);
        };

        dump([
            "mask" => $formatter($bitmask, $bitmask->getMask()),
            "flipped" => $formatter($bitmask, $bitmask->getFlippedMask()),
        ]);
    }
}
