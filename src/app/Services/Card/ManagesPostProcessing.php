<?php

namespace App\Services\Card;

use App\Exceptions\CrudException;
use App\Models\CardSet;
use App\Models\Card;

/**
 * This trait manipulates data after all Card input processors executed
 * Accesses these properties: $old, $new, $state
 */
trait ManagesPostProcessing
{
    /**
     * Checks if passed set/number combination already exists in the database
     * Throws an exception if so
     *
     * @return void
     */
    public function checkExistingNumber(): void
    {
        if (!empty($this->old)) return;

        // Check for an existing card with this set/number combination
        $existing = database()
            ->select(
                statement('select')
                    ->select('id')
                    ->from('cards')
                    ->where('sets_id = :set AND num = :num')
                    ->limit(1)
            )
            ->bind([
                ':set' => $this->state['set-id'],
                ':num' => $this->new['num']
             ])
            ->get();

        // ERROR: Number already exists!
        if (!empty($existing)) {
            $num = &$this->state['number-padded'];
            $set = &$this->state['set-code'];
            throw new CrudException(
                collapse(
                    "Card with number <strong>{$num}</strong> from set ",
                    "<strong>{$set}</strong> already exists"
                )
            );
        }
    }
    
    /**
     * Checks if a card with the same name and NARP = 0 (base print)
     * already exists. Any card having the same name as a base print MUST
     * be an alternate, promo or reprint as names are unique in Force of Will!
     *
     * @return void
     */
    public function checkExistingName(): void
    {
        if (!empty($this->old)) return;

        if ($this->new['narp'] !== '0') return;

        $existing = database()
            ->select(
                statement('select')
                    ->select('id')
                    ->from('cards')
                    ->where('name = :name')
                    ->where('narp = 0')
            )
            ->bind([
                ':name' => $this->new['name']
            ])
            ->first();

        // ERROR: Number already exists!
        if (!empty($existing)) {
            throw new CrudException(
                "A basic print of card <strong>{$this->new['name']}</strong> already exists, please flag it as an Alternate, Reprint or Promo with the NARP input"
            );
        }
    }

    /**
     * Calculates the total cost via state variables set by 
     * the processing functions for costs
     *
     * @return void
     */
    public function calculateTotalCost(): void
    {
        $totalCost = null;

        if (isset($this->state['attribute-cost'])) {
            $totalCost += $this->state['attribute-cost'];
        }

        if (isset($this->state['free-cost'])) {
            $totalCost += $this->state['free-cost'];
        }

        $this->new['total_cost'] = $totalCost;
    }
    
    /**
     * A card's code usually gets automatically generated here
     * 
     * Pattern: %SETCODE-%NUMBER%SPACE%RARITY
     * %SETCODE Three-letters-long upper case set code (Ex.: NDR, WOM)
     * %NUMBER  Left-padded three-digits-long card number (Ex.: 001, 042)
     * %SPACE   Just a single whitespace
     * %RARITY  Uppercase of the card's rarity code (Ex.: U, C, SR)
     * 
     * Cards with an unusual code (like promos) accept a custom code on
     * the cards creation page
     *
     * @return void
     */
    public function calculateCodeField(): void
    {
        if (isset($this->new['code'])) return;

        // Ex.: "NDR-001"
        $set = strtoupper($this->state['set-code']);
        $num = $this->state['number-padded'];
        $code = "{$set}-{$num}";

        // Ex.: "NDR-001 U"
        if ($this->new['rarity'] !== null) {
            $rarity = strtoupper($this->new['rarity']);
            $code .= " {$rarity}";
        }

        $this->new['code'] = $code;
    }

    /**
     * Calculates the cluster's ID from this card's set ID
     *
     * @return void
     */
    public function calculateCluster(): void
    {
        $set = (new CardSet)->byId($this->state['set-id'], ['clusters_id']);
        $this->new['clusters_id'] = $set['clusters_id'];
    }

    /**
     * Calculates the relative paths for card images to be stored into the db
     *
     * @return void
     */
    private function calculateImagePaths(): void
    {
        // Calculate the suffix
        ($this->new['back_side'] !== '0')
            ? $suffix = lookup("backsides.id2code.{$this->new['back_side']}")
            : $suffix = '';
        
        // Assemble the template for this card's image paths
        // Template: '{CLUSTER}/{SET}/{NUMBER}{SUFFIX}.jpg'
        $path = $this->new['clusters_id'] . '/'
              . $this->state['set-code'] . '/'
              . $this->state['number-padded']
              . $suffix
              . '.jpg';

        $this->new['image_path'] = "images/cards/{$path}";
        $this->new['thumb_path'] = "images/thumbs/{$path}";
    }

    /**
     * Remove illegal fields like costs, attribute and battle values
     * Based on the card type
     *
     * @return void
     */
    private function removeIllegalFields(): void
    {
        $removables = (new Card)->getRemovableFields();

        // Remove costs
        if (in_array($this->new['type'], $removables['no-cost'])) {
            $this->new['attribute_cost'] = null;
            $this->new['free_cost'] = null;
            $this->new['total_cost'] = null;
        }

        // Remove attribute
        if (in_array($this->new['type'], $removables['no-attribute'])) {
            $this->new['attribute'] = null;
        }

        // Remove ATK and DEF
        if (!in_array($this->new['type'], $removables['can-battle'])) {
            $this->new['atk'] = null;
            $this->new['def'] = null;
        }

        // A resonator must have ATK and DEF values!
        if (
            $this->new['type'] === 'Resonator' &&
            !isset($this->new['atk']) &&
            !isset($this->new['def'])
        ) {
            throw new CrudException(
                "A card with type \"Resonator\" must have ATK and DEF values"
            );
        }
    }
}
