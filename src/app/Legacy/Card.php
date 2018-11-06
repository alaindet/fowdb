<?php

namespace App\Legacy;

use App\Models\Ban;
use App\Models\Card as Model;
use App\Models\CardNarp;
use App\Models\Card as CardModel;
use App\Models\Ruling;
use App\Utils\Arrays;
use App\Views\Card\Card as View;

class Card
{
    public static function getCardPageData(): array
    {
        $code = htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8');
        $cardsDb = Model::getByCode($code);

        // ERROR: No card with that code!
        if (empty($cardsDb)) {
            alert(
                "No card found with code <strong>{$code}</strong>",
                'warning'
            );
            redirect_old('/');
        }

        $results = true;
        $cards = [];

        foreach ($cardsDb as &$card) {
            
            // $type ----------------------------------------------------------
            $reminder = ($card['back_side'] === '2') ? ' (Shift)' : '';
            $link = "/?do=search&type[]={$card['type']}";
            $type = "<a href=\"{$link}\">{$card['type']}</a>{$reminder}";
            
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

            // $attributecost -------------------------------------------------
            $attributecost = '';
            if (!empty($card['attribute_cost'])) {
                $attributecost = array_reduce(
                    str_split($card['attribute_cost']),
                    function ($tot, $attr) { return $tot .= render('{'.$attr.'}'); },
                    ''
                );
            }
            
            // $cost ----------------------------------------------------------
            $cost = empty($card['total_cost']) ? '0' : $attributecost . $freecost;
            
            // $attribute -----------------------------------------------------
            $attribute = '';
            if (!empty($card['attribute'])) {

                // Build attribute html
                // Ex.: [ICON] Fire, [ICON] Dark
                $attributesMap = cached('attributes');
                $attributes = [];
                foreach (explode('/', $card['attribute']) as $attribute) {
                    $attributes[] = collapse(
                        '<a href="/?do=search&attributes[]=',$attribute,'">',
                            '<img ',
                                'src="',asset('images/icons/blank.gif'),'" ',
                                'class="fd-icon-',$attribute,'"',
                            '>&nbsp;',
                            $attributesMap[$attribute],
                        '</a>'
                    );
                }
                $attribute = implode(', ', $attributes);
            }
            
            // $raceLabel -----------------------------------------------------
            $raceTypes = ['Ruler', 'J-Ruler', 'Resonator'];
            $raceLabel = in_array($card['type'], $raceTypes) ? 'race' : 'trait';

            // $raceValue -----------------------------------------------------
            $raceValue = '<em>(none)</em>';
            if (!empty($card['race'])) {
                $raceValue = implode(' / ', array_map(
                    function ($race) {
                        return "<a href=\"/?do=search&race={$race}\">{$race}</a>";
                    },
                    explode('/', $card['race'])
                ));
            }
            
            // $set -----------------------------------------------------------
            $setId =& $card['sets_id'];
            $setCode = lookup("sets.id2code.{$setId}");
            $setName = lookup("sets.id2name.{$setId}");
            $set = collapse(
                "<a href='/?do=search&set={$setCode}'>",
                    strtoupper($setCode),' - ',$setName,
                "</a>"
            );

            // $artist --------------------------------------------------------
            $artist = null;
            if (isset($card['artist_name'])) {
                $artist = collapse(
                    "<a href='/?do=search&artist={$card['artist_name']}'>",
                        $card['artist_name'],
                    "</a>"
                );
            }

            // $baseCardId ----------------------------------------------------
            ($card['narp'] === 0)
                ? $baseCardId = (int) $card['id']
                : $baseCardId = CardModel::getBaseIdByName($card['name']);
            
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
                // $bannedFormats = Ban::formatsList($baseCardId);
                $bannedFormats = Ban::getData('card', $baseCardId);

                // Is this banned?
                if (!empty($bannedFormats)) {

                    // Exclude banned formats
                    // Ex.: (assoc) [ [format_name, format_code] ]
                    $bannedFormatsNames = array_column($bannedFormats, 'name');
                    $cardFormats = array_filter(
                        $cardFormats,
                        function ($format) use ($bannedFormatsNames) {
                            return in_array(
                                $format['name'],
                                $bannedFormatsNames
                            );
                        }
                    );

                    // Built HTML list of banned formats
                    // Ex.: (no extra) New Frontiers
                    // Ex.: (extra) New Frontiers (Rune Deck, 1 copy)
                    $bannedHtml = implode(', ', array_map(function ($ban) {
                        
                        $extra = Arrays::filterNull([
                            $ban['deck'],
                            $ban['copies']
                        ]);

                        return collapse(
                            '<span style="color:red;">',
                                '<strong>',$ban['format'],'</strong>&nbsp',
                            '</span>',
                            '<em>',
                                !empty($extra)
                                    ? '('.implode(', ', $extra).')'
                                    : '',
                            '</em>'
                        );

                    }, $bannedFormats));
                }

                // $format, $banned -------------------------------------------
                $format = View::displayFormats($cardFormats);
                $banned = $bannedHtml ?? '';
            }
            
            // $rulings -------------------------------------------------------
            $rulings = Ruling::getByCardId($baseCardId, $render = true);

            // $narp ----------------------------------------------------------
            $narp = CardNarp::displayRelatedCards(
                (int) $card['narp'],
                $card['name']
            );

            // $rarity --------------------------------------------------------
            $rarity = '<em>(none)</em>';
            $rarityCode =& $card['rarity'];
            $rarityName = lookup("rarities.code2name.{$rarityCode}");
            if (isset($card['rarity'])) {
                $rarity = collapse(
                    '<a href="/?do=search&rarity[]=',$card['rarity'],'">',
                        strtoupper($rarityCode),' - ',$rarityName,
                    '</a>'
                );
            }

            // $flavorText ----------------------------------------------------
            $flavorText = collapse(
                '<span class="flavortext">',
                    $card['flavor_text'],
                '</span>'
            );

            // $atkDef -------------------------------------------------------------------
            $atkDef = collapse(
                '<span class="font-150 text-italic">',
                    $card['atk'],' / ',$card['def'],
                '</span>'
            );

            // Backup card type before overwriting $cards
            $_type = $card['type'];

            // $card -------------------------------------------------------------------
            $card = [

                // Shown info (side panel)
                'name' => $card['name'],
                'cost' => $cost,
                'total_cost' => $card['total_cost'],
                'atk_def' => $atkDef,
                'divinity' => $card['divinity'],
                'type' => $type,
                $raceLabel => $raceValue,
                'attribute' => $attribute,
                'text' => render($card['text']),
                'flavor_text' => $flavorText,
                'code' => $card['code'],
                'rarity' => $rarity,
                'artist_name' => $artist,
                'set' => $set,
                'format' => $format,
                'banned' => $banned,

                // Extra info
                'id' => $card['id'],
                'back_side' => $card['back_side'],
                'narp' => $narp,
                'image_path' => $card['image_path'],
                'thumb_path' => $card['thumb_path'],
                'rulings' => $rulings,
                
            ];

            // Remove optional properties for some card types -------------------------------------------------------------------
            if (empty($card['banned'])) unset($card['banned']);
            if (empty($card['divinity'])) unset($card['divinity']);
            if (empty($card['flavor_text'])) unset($card['flavor_text']);

            // Filter out some props based on card type
            $cards[] = View::removeIllegalProps($card, $_type);
        }

        // Add the display property on each card
        View::addDisplay($cards);

        return $cards;
    }
}
