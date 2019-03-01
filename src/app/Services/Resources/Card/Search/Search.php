<?php

namespace App\Services\Resources\Card\Search;

use App\Base\Search\Search as BaseSearch;
use App\Base\Search\SearchInterface;
use App\Services\Lookup\Lookup;
use App\Services\Resources\Card\Search\BeforeProcessingTrait;
use App\Services\Resources\Card\Search\ParameterProcessorsTrait;
use App\Services\Resources\Card\Search\AfterProcessingTrait;

class Search extends BaseSearch
{
    use BeforeProcessingTrait;
    use ParameterProcessorsTrait;
    use AfterProcessingTrait;

    protected $parameterProcessors = [
        'atk'                => 'processAtkValueParameter',
        'atk-operator'       => 'processAtkOperatorParameter',
        'attribute'          => 'processAttributeParameter',
        'attribute-multi'    => 'processMultiAttributeParameter',
        'attribute-single'   => 'processSingleAttributeParameter',
        'attribute-selected' => 'processSelectedAttributeParameter',
        'back-side'          => 'processBackSideParameter',
        'cluster'            => 'processClusterParameter',
        'cost'               => 'processCostParameter',
        'cost-free'          => 'processFreeCostParameter',
        'cost-x'             => 'processCostXParameter',
        'def'                => 'processDefValueParameter',
        'def-operator'       => 'processDefOperatorParameter',
        'divinity'           => 'processDivinityParameter',
        'partial-match'      => 'processPartialMatchParameter',
        'exclude'            => 'processExcludeParameter',
        'format'             => 'processFormatParameter',
        'illust'             => 'processIllustratorParameter',
        'in-fields'          => 'processInFieldsParameter',
        'q'                  => 'processQueryParameter',
        'race'               => 'processRaceParameter',
        'rarity'             => 'processRarityParameter',
        'set'                => 'processSetParameter',
        'sort'               => 'processSortParameter',
        'sort-dir'           => 'processSortDirectionParameter',
        'type'               => 'processTypeParameter',
        'type-selected'      => 'processTypeSelectedParameter',
    ];

    protected $parameterAliases = [
        'artist'             => 'illust',
        'attribute-multi'    => 'attribute-multi',
        'attribute_multi'    => 'attribute-multi',
        'attribute_selected' => 'attribute-selected',
        'attributes'         => 'attributes',
        'attrmulti'          => 'attribute-multi',
        'attrselected'       => 'attribute-selected',
        'backside'           => 'back-side',
        'illustrator'        => 'illust',
        'infields'           => 'in-fields',
        'no_attribute_multi' => 'attribute-single',
        'setcode'            => 'set',
        'sortdir'            => 'sort-dir',
        'total_cost'         => 'cost',
        'type_selected'      => 'type-selected',
        'xcost'              => 'cost-x',
    ];

    protected $lookup;

    public function __construct()
    {
        parent::__construct();
        $this->lookup = Lookup::getInstance();
    }

    /**
     * Defines an abstract method
     *
     * @return void
     */
    protected function setDefaults(): SearchInterface
    {
        $this->statement->from('cards');

        $this->statement->fields([
            'id',
            'sets_id',
            'num',
            'code',
            'name',
            'thumb_path',
            'image_path',
        ]);

        return $this;
    }
}
