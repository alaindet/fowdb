<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Entity\PlayRestriction\PlayRestriction;
use App\Entity\GameFormat\GameFormat;

class BannedGenerator implements LookupDataGeneratorInterface
{
    /**
     * Returns a map from format code to banned cards in that format
     * Formats are multi-cluster *ONLY*
     * 
     * Ex.:
     * {
     *   "origin": [ 123, 124, 156, ... ],
     *   "wandr": [...]
     * }
     *
     * @return object
     */
    public function generate(): object
    {
        // STRUCTURE
        // =========
        // [
        //     format_code => [
        //         card_id,
        //         ...
        //     ],
        //     ...
        // ]
        $result = new \stdClass();

        $formatsId2Code = fd_repository(GameFormat::class)
            ->setReplaceStatement(fd_statement("select")->select(["id", "code"]))
            ->findAllBy("is_multi_cluster", 1)
            ->reduce(
                function ($map, $format) use (&$result) {
                    $map->{"id".$format->id} = $format->code;
                    $result->{$format->code} = []; // WATCH OUT!
                    return $map;
                },
                new \stdClass()
            );

        $playRestrictions = fd_repository(PlayRestriction::class)->all();

        foreach ($playRestrictions as $item) {
            $formatIdLabel = "id" . $item->formats_id;
            if (isset($formatsId2Code->{$formatIdLabel})) {
                $formatCode = $formatsId2Code->{$formatIdLabel};
                $result->{$formatCode}[] = $item->cards_id;
            }
        }

        return $result;
    }
}
