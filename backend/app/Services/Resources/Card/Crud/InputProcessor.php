<?php

namespace App\Services\Resources\Card\Crud;

use App\Base\InputProcessor as BaseInputProcessor;
use App\Services\Resources\Card\Crud\PostProcessingTrait;
use App\Utils\Bitmask;

class InputProcessor extends BaseInputProcessor
{
    use PostProcessingTrait;

    /**
     * Maps the input name to its processor function. Order is important
     *
     * @var array
     */
    protected $functions = [
        'narp' => 'processNarpInput',
        'set' => 'processSetInput',
        'number' => 'processNumberInput',
        'layout' => 'processLayoutInput',
        'name' => 'processNameInput',
        'code' => 'processCodeInput',
        'rarity' => 'processRarityInput',
        'attribute' => 'processAttributeInput',
        'type' => 'processTypeInput',
        'attribute-cost' => 'processAttributeCostInput',
        'free-cost' => 'processFreeCostInput',
        'total-cost' => 'processTotalCostInput',
        'divinity-cost' => 'processDivinityCostInput',
        'atk' => 'processAtkInput',
        'def' => 'processDefInput',
        'race' => 'processRaceInput',
        'text' => 'processTextInput',
        'flavor-text' => 'processFlavorTextInput',
        'artist-name' => 'processArtistNameInput',
    ];

    /**
     * On create: required
     * On update: optional
     *
     * @param array $value
     * @return void
     */
    public function processNarpInput(string $value = null): void
    {
        $this->new['narp'] = $value ?? '0';
    }

    /**
     * Required. Sets a state variable
     * 
     * Immutable on updating
     *
     * @param array $value
     * @return void
     */
    public function processSetInput(string $value = null): void
    {
        $map = lookup('sets.code2id');
        $setId = intval($map[$value]);

        $this->state['set-code'] = $value;
        $this->state['set-id'] = $setId;

        $this->new['sets_id'] = $this->state['set-id'];
    }

    /**
     * Required. Sets a state variable
     * 
     * Immutable on updating
     *
     * @param array $value
     * @return void
     */
    public function processNumberInput(string $value = null): void
    {
        // Generate the padded number (for later use)
        $this->state['number-padded'] = str_pad($value, 3, '0', STR_PAD_LEFT);

        // Store the card's number
        $this->new['num'] = intval($value);
    }

    /**
     * Optional
     * Reset via (Basic) button ($value == 0)
     * 
     * Immutable on updating
     *
     * @param array $value
     * @return void
     */
    public function processLayoutInput(string $value = null): void
    {
        $this->new['layout'] = $value ?? '0';
    }

    /**
     * Required
     *
     * @param array $value
     * @return void
     */
    public function processNameInput(string $value = null): void
    {
        $this->new['name'] = $value;
    }

    public function processCodeInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['code'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['code'] = $value;
        }
    }

    /**
     * Optional
     *
     * @param string $value
     * @return void
     */
    public function processRarityInput(string $value = null): void
    {
        $this->new['rarity'] = ($value === '0') ? null : $value;
    }

    /**
     * Required
     *
     * @param array $value
     * @return void
     */
    public function processAttributeInput(array $value = null): void
    {
        // Reset
        if (in_array('32', $value)) {
            $this->new['attribute_bit'] = 0;
        }
        
        else {
            $this->new['attribute_bit'] = (new Bitmask)
                ->addBitValues($value)
                ->getMask();
        }
    }

    /**
     * Required
     *
     * @param string $value
     * @return void
     */
    public function processTypeInput(array $value = null): void
    {
        $this->new['type_bit'] = (new Bitmask)
            ->addBitValues($value)
            ->getMask();
    }

    /**
     * Optional field, can be reset, sets a state variable
     *
     * @param string $value
     * @return void
     */
    public function processAttributeCostInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['attribute_cost'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['attribute_cost'] = $value;
        }

        $this->state['attribute-cost'] = $value;
    }

    /**
     * Optional
     *
     * @param string $value
     * @return void
     */
    public function processFreeCostInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['free_cost'] = null;
        }

        elseif ($value !== null && $value !== '') {

            // X costs
            if (substr($value, 0, 1) === 'x') {
                $this->new['free_cost'] = -1 * strlen($value);
                $this->state['free-cost'] = 0;
            }
            
            // Normal numeric free costs
            else {
                $this->new['free_cost'] = $value;
                $this->state['free-cost'] = intval($value);
            }
            
        }
    }

    /**
     * Optional
     *
     * @param string $value
     * @return void
     */
    public function processTotalCostInput(string $value = null): void
    {
        if (isset($value)) {
            $this->state['total-cost'] = $value;
        }
    }

    /**
     * Optional
     *
     * @param string $value
     * @return void
     */
    public function processDivinityCostInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['divinity'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['divinity'] = $value;
        }
    }

    /**
     * Optional
     * Required on Ruler, J-Ruler and Resonator types
     * Forbidden on any other type
     *
     * @param string $value
     * @return void
     */
    public function processAtkInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['atk'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['atk'] = $value;
        }
    }

    /**
     * Optional
     * Required on Ruler, J-Ruler and Resonator types
     * Forbidden on any other type
     *
     * @param string $value
     * @return void
     */
    public function processDefInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['def'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['def'] = $value;
        }
    }

    public function processRaceInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['race'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['race'] = $value;
        }
    }

    public function processTextInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['text'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['text'] = trim($value);
        }
    }

    public function processFlavorTextInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['flavor_text'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['flavor_text'] = trim($value);
        }
    }

    public function processArtistNameInput(string $value = null): void
    {
        // Reset
        if ($value === '-1') {
            $this->new['artist_name'] = null;
        }

        elseif ($value !== null && $value !== '') {
            $this->new['artist_name'] = $value;
        }
    }
}
