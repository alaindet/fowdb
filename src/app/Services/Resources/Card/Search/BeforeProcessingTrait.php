<?php

namespace App\Services\Resources\Card\Search;

use App\Base\Search\SearchInterface;
use App\Models\CardType;
use App\Services\Resources\Card\Search\SearchBarProcessorTrait;
use App\Utils\BitmaskFlags;
use App\Utils\Bitmask;
use App\Utils\Arrays;

/**
 * From App\Services\Resources\Card\Search\Search
 * 
 * protected $statement;
 * protected $bind;
 * protected $state;
 * protected $parameters
 * protected $lookup
 */
trait BeforeProcessingTrait
{
    protected function beforeProcessing(): SearchInterface
    {
        $this->beforeNormalizeParameters();
        $this->beforeDefaultStateVariables();
        return $this;
    }

    /**
     * Normalizes inputs so that legacy inputs (ex.: string[] instead of string)
     * Are accepted and then normalized to current format
     * Updates the input parameters
     *
     * @return void
     */
    private function beforeNormalizeParameters(): void
    {
        $params = &$this->parameters;

        // 'format' must be a string
        if (isset($params['format']) && is_array($params['format'])) {
            $params['format'] = $params['format'][0];
        }

        // 'back-side' must be an array (allows strings)
        if (isset($params['back-side']) && !is_array($params['back-side'])) {
            $params['back-side'] = [ $params['back-side'] ];
        }
    }

    private function beforeDefaultStateVariables(): void
    {
        $this->state['query'] = '';
        $this->state['query-fields'] = [];
        $this->state['query-exact'] = false;

        $this->state['attributes'] = [];
        $this->state['attributes-selected'] = false;
        $this->state['attributes-only-single'] = false;
        $this->state['attributes-only-multi'] = false;

        $this->state['atk-operator'] = 'equals';
        $this->state['def-operator'] = 'equals';

        $this->state['sort'] = null;
        $this->state['sort-direction'] = 'asc';
        $this->state['sort-default'] = 'sorted_id DESC';

        $this->state['types'] = [];
        $this->state['types-selected'] = false;

        $this->state['cost'] = [];
        $this->state['cost-x'] = false;
    }
}
