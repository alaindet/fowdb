<?php

namespace App\Legacy;

use App\Models\PlayRestriction;
use App\Models\CardNarp;
use App\Models\Card as Model;
use App\Models\CardType;
use App\Models\GameRuling;
use App\Utils\Arrays;
use App\Views\Card\Card as View;
use App\Utils\BitmaskFlags;

class Card
{
    public static function getCardPageData(string $code): array
    {
    	$cardModel = new Model;
        $cardsDb = $cardModel->getByCode($code);

        // ERROR: No card with that code!
        if (empty($cardsDb)) {
            alert("No card found with code <strong>{$code}</strong>",'warning');
            redirect('/');
        }

        $results = true;
        $cards = [];

        foreach ($cardsDb as &$card) {

            // $type ----------------------------------------------------------
            $_type = [];
            $typeLabels = View::buildTypeLabels(intval($card['type_bit']));
            foreach ($typeLabels as $typeLabel) {
                $link = url('cards', ['type' => [$typeLabel]]);
                $_type[] = "<a href=\"{$link}\">{$typeLabel}</a>";
            }
            $type = implode(' / ', $_type);
            if ($card['layout'] === '2') $type .= ' (Shift)';

            // $attributecost -------------------------------------------------
            $attributecost = '';
            if (!empty($card['attribute_cost'])) {
                $attributecost = array_reduce(
                    str_split($card['attribute_cost']),
                    function ($tot, $attr) { return $tot .= render('{'.$attr.'}'); },
                    ''
                );
            }

            // $freecost ------------------------------------------------------
            $freecost = '';
            if (!empty($card['free_cost'])) {
                if ($card['free_cost'] > 0) {
                    $freecost = render('{'.$card['free_cost'].'}');
                } else {
                    $xCosts = '';
                    for ($i = $card['free_cost']; $i < 0; $i++) {
                        $xCosts .= '{x}';
                    }
                    $freecost = render($xCosts);
                }
            }
            
            // $cost ----------------------------------------------------------
            // $cost = empty($card['total_cost']) ? '0' : $attributecost . $freecost;
            $cost = $attributecost . $freecost;

            // $totalCost -----------------------------------------------------
            $link = url('cards', ['total_cost' => [$card['total_cost']]]);
            $totalCost = "<a href=\"{$link}\">{$card['total_cost']}</a>";
            
            // $attribute -----------------------------------------------------
            $attribute = '';
            if ($card['attribute_bit'] > 0) {

                $bitmask = (new BitmaskFlags)->setMask($card['attribute_bit']);
                $name2bit = lookup('attributes.name2bit');
                $code2bit = lookup('attributes.code2bit');
                $names = $bitmask->setFlagsMap($name2bit)->readFlags();
                $codes = $bitmask->setFlagsMap($code2bit)->readFlags();

                $temp = [];
                for ($i = 0, $ii = count($names); $i < $ii; $i++) {
                    $link = url('cards', ['attribute' => [$codes[$i]]]);
                    $temp[] = (
                        '<a href="'.$link.'">'.
                            '<img '.
                                'src="'.asset('images/icons/blank.gif').'" '.
                                'class="fd-icon-'.$codes[$i].'"'.
                            '>&nbsp;'.
                            $names[$i].
                        '</a>'
                    );
                }
                $attribute = implode(', ', $temp);
            }
            
            // $raceLabel -----------------------------------------------------

            $raceTypeLabels = CardType::$withRace;
            (array_diff($raceTypeLabels, $typeLabels) === $raceTypeLabels)
                ? $raceLabel = 'trait'
                : $raceLabel = 'race';

            // $raceValue -----------------------------------------------------
            $raceValue = '<em>(None)</em>';
            if (!empty($card['race'])) {
                $raceValue = implode(' / ', array_map(
                    function ($race) {
                        $link = url('cards', [ 'race' => $race ]);
                        return "<a href=\"{$link}\">{$race}</a>";
                    },
                    explode('/', $card['race'])
                ));
            }
            
            // $set -----------------------------------------------------------
            $setId =& $card['sets_id'];
            $setCode = lookup("sets.id2code.{$setId}");
            $setName = lookup("sets.id2name.{$setId}");
            $link = url('cards', [ 'set' => $setCode ]);
            $set = (
                '<a href="'.$link.'">'.
                    strtoupper($setCode).' - '.$setName.
                '</a>'
            );

            // $artist --------------------------------------------------------
            $artist = null;
            if (isset($card['artist_name'])) {
                $link = url('cards', ['artist' => $card['artist_name']]);
                $artist = "<a href=\"{$link}\">{$card['artist_name']}</a>";
            }

            // $baseCardId ----------------------------------------------------
            ($card['narp'] === 0)
                ? $baseCardId = (int) $card['id']
                : $baseCardId = $cardModel->getBaseIdByName($card['name']);
            
            // $format, $banned -----------------------------------------------
            $spoilers = lookup('spoilers.ids');
            if (!empty($spoilers) && in_array($card['sets_id'], $spoilers)) {
                $format = '<span class="fd-mark-spoiler">Spoiler</span>';
                $banned = '';
            }

            // Not a spoiler card, check the banned list
            else {
                
                // This card's formats
                // Ex.: (assoc) [ [format_name, format_code], ... ]
                $cardFormats = View::formatsListByCluster($card['clusters_id']);

                // Banned in these formats (can be empty, most of the times)
                // Ex.: (assoc) [ [format_name, deck_name, copies_in_deck], .. ]
                $bannedFormats = PlayRestriction::getData('card', $baseCardId);

                // Is this banned?
                if (!empty($bannedFormats)) {

                    // Extract names of the formats in which this card is banned
                    $bannedFormatsNames = [];
                    foreach ($bannedFormats as $bannedFormat) {
                        $name = $bannedFormat["format"];
                        if (!isset($bannedFormatsNames[$name])) {
                            $bannedFormatsNames[$name] = true;
                        }
                    }

                    // Exclude formats in which this card is banned
                    $cardFormats = array_filter(
                        $cardFormats,
                        function ($cardFormat) use ($bannedFormatsNames) {
                            $name = $cardFormat["name"];
                            return !isset($bannedFormatsNames[$name]);
                        }
                    );

                    $bannedElements = [];
                    foreach ($bannedFormats as $bannedFormat) {
                        $extra = Arrays::filterNull([
                            $bannedFormat["deck"],
                            $bannedFormat["copies"],
                        ]);
                        $bannedElements[] = (
                            "<span style=\"color:red;\">".
                                "<strong>{$bannedFormat["format"]}</strong>&nbsp".
                                (!empty($extra)
                                    ? "(".implode(", ", $extra).")"
                                    : ""
                                ).
                            "</span>"
                        );
                    }
                    $bannedHtml = implode(", ", $bannedElements);
                }

                // $format, $banned -------------------------------------------
                $format = View::displayFormats($cardFormats);
                $banned = $bannedHtml ?? "";
            }
            
            // $rulings -------------------------------------------------------
            $rulings = (new GameRuling)->byCardId(
                $baseCardId,
                $fields = ['id', 'date', 'is_errata', 'text'],
                $fieldsToRender = ['text']
            );

            // $narp ----------------------------------------------------------
            $narp = CardNarp::displayRelatedCards(
                (int) $card['narp'],
                $card['name']
            );

            // $rarity --------------------------------------------------------
            $rarity = '<em>(None)</em>';
            $rarityCode =& $card['rarity'];
            $rarityName = lookup("rarities.code2name.{$rarityCode}");
            if (isset($card['rarity'])) {
                $link = url('cards', [ 'rarity' => [$card['rarity']] ]);
                $rarity = (
                    '<a href="'.$link.'">'.
                        strtoupper($rarityCode).' - '.$rarityName.
                    '</a>'
                );
            }

            // $flavorText ----------------------------------------------------
            $flavorText = null;
            if (isset($card['flavor_text'])) {
                $flavorText = (
                    '<span class="flavortext">'.
                        $card['flavor_text'].
                    '</span>'
                );    
            }

            // $atkDef --------------------------------------------------------

            // No battle values
            if (!isset($card['atk']) && !isset($card['def'])) {
                $atkDef = '<em>(No battle values)</em>';
            } else {
                $atkDef = (
                    '<span class="font-150 text-italic">'.
                        $card['atk'].' / '.$card['def'].
                    '</span>'
                );
            }

            // $divinity ------------------------------------------------------
            $divinity = null;
            if (isset($card['divinity'])) {
                $label = fd_divinity($card['divinity']);
                $link = url('cards', ['divinity' => [$card['divinity']]]);
                $divinity = "<a href=\"{$link}\">{$label}</a>";
            }

            // $card ----------------------------------------------------------
            // Backup card type before overwriting $cards
            $_type = $card['type_bit'];

            $card = [

                // Shown info (side panel)
                'name' => $card['name'],
                'type' => $type,
                'cost' => $cost,
                'total_cost' => $totalCost,
                'atk_def' => $atkDef,
                'divinity' => $divinity,
                $raceLabel => $raceValue,
                'attribute' => $attribute,
                'text' => !empty($card['text']) ? render($card['text']) : null,
                'flavor_text' => $flavorText,
                'code' => $card['code'],
                'rarity' => $rarity,
                'artist_name' => $artist,
                'set' => $set,
                'format' => $format,
                'banned' => $banned,

                // Extra info
                'id' => $card['id'],
                'sorted_id' => $card['sorted_id'],
                'layout' => $card['layout'],
                'narp' => $narp,
                'image_path' => $card['image_path'],
                'thumb_path' => $card['thumb_path'],
                'rulings' => $rulings,
                'is_banned' => ($banned !== ''),
                
            ];

            // Remove optional properties for some card types -------------------------------------------------------------------
            if (empty($card['banned'])) unset($card['banned']);
            if (empty($card['divinity'])) unset($card['divinity']);
            if (empty($card['flavor_text'])) unset($card['flavor_text']);

            // Filter out some props based on card type
            $cards[] = View::removeIllegalProps($card, $_type);
            // $cards[] = $card;
        }

        // Add the display property on each card
        View::addDisplay($cards);

        return $cards;
    }
}
