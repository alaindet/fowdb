<?php

namespace App\Entities\Card;

use App\Base\Entities\Entity;
use App\Entities\Card\CardDatabasePropertiesTrait;
use App\Entities\Card\CardComputedPropertiesTrait;

class Card extends Entity
{
    use CardDatabasePropertiesTrait;
    use CardComputedPropertiesTrait;

    /**
     * Define computed properties and its property accessors
     *
     * @var array
     */
    protected $propertyGetters = [

        // HTML presentation properties
        'html_name' => 'getHtmlNameProperty',
        'html_type' => 'getHtmlTypeProperty',
        'html_cost' => 'getHtmlCostProperty',
        'html_total-cost' => 'getHtmlTotalCostProperty',
        'html_battle-values' => 'getHtmlBattleValuesProperty',
        'html_divinity' => 'getHtmlDivinityProperty',
        'html_race' => 'getHtmlRaceProperty',
        'html_attribute' => 'getHtmlAttributeProperty',
        'html_text' => 'getHtmlTextProperty',
        'html_flavor-text' => 'getHtmlFlavorTextProperty',
        'html_code' => 'getHtmlCodeProperty',
        'html_rarity' => 'getHtmlrarityProperty',
        'html_artist' => 'getHtmlArtistProperty',
        'html_set' => 'getHtmlSetProperty',
        'html_cluster' => 'getHtmlClusterProperty',
        'html_format' => 'getHtmlFormatProperty',
        'html_banned' => 'getHtmlBannedProperty',
        'html_image' => 'getHtmlImageProperty',

        // Presentation properties
        'formats' => 'getFormatsProperty',
        'type_names' => 'getTypeNamesProperty',
        'attribute_codes' => 'getAttributeCodesProperty',
        'attribute_names' => 'getAttributeNamesProperty',

        // Relationships properties
        // 'cluster' => 'getClusterProperty',
        // 'set' => 'getSetProperty',
        // 'narp' => 'getNarpProperty',
        // 'rulings' => 'getRulingsProperty'

    ];
}
