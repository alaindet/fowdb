<?php

namespace App\Services\Resources\Card\Crud;

use App\Exceptions\CrudException;
use App\Models\GameSet;
use App\Models\Card;
use App\Utils\Bitmask;
use App\Utils\BitmaskFlags;
use App\Utils\Uri;
use App\Views\Card\Card as ViewCard;

/**
 * This trait manipulates data after all Card input processors executed
 * Accesses these properties: $old, $new, $state
 */
trait PostProcessingTrait
{
    /**
     * Overrides App\Base\InputProcessor::afterProcessing
     * 
     * Calculate total cost, sorted ID and image paths
     * Remove illegal fields like ATK and DEF from Chants
     *
     * @return void
     */
    public function afterProcessing(): void
    {
        $this->checkExistingNumber();
        $this->checkExistingName();
        $this->calculateTotalCost();
        $this->calculateCodeField();
        $this->calculateCluster();
        $this->calculateImagePaths();
        $this->calculateLegality();
        $this->removeIllegalFields();
    }

    /**
     * Checks if passed set/number/layout combination already exists
     * in the database and throws an exception if so
     *
     * @return void
     */
    public function checkExistingNumber(): void
    {
        $statement = statement('select')
            ->select([
                'id',
                'layout',
            ])
            ->from('cards')
            ->where('sets_id = :set')
            ->where('num = :num')
            ->where('layout = :layout')
            ->limit(1);

        $bind = [
            ':set' => $this->state['set-id'],
            ':num' => $this->new['num'],
            ':layout' => $this->new['layout'],
        ];

        if (!empty($this->old)) {
            $statement->where('id <> :cardid');
            $bind[':cardid'] = $this->old['id'];
        }

        // Check for an existing card with this set/number/layout combination
        $existing = database()
            ->select($statement)
            ->bind($bind)
            ->get();

        // Alternative cards make an exception as the two halves have the same
        // set/number/layout combination
        $layouts = array_flip(lookup('layouts.id2name'));

        // ERROR: Number already exists!
        if (
            !empty($existing) &&    
            $existing[0]['layout'] !== (string) $layouts['Alternative']
        ) {
            $num = &$this->state['number-padded'];
            $set = strtoupper($this->state['set-code']);
            throw new CrudException(
                "Card with number <strong>{$num}</strong> from set ".
                "<strong>{$set}</strong> already exists"
            );
        }
    }
    
    /**
     * Checks if a card with the same name and NARP = 0 (base print)
     * already exists. Any card having the same name as a base print MUST
     * be an alternate art, promo or reprint as names are unique in Force of Will!
     *
     * @return void
     */
    public function checkExistingName(): void
    {
        if (!empty($this->old)) return;

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

        // ERROR: Trying to add a NARP = 0 but it already exists
        if (!empty($existing) && $this->new['narp'] === '0') {
            throw new CrudException(
                "A basic print of card <strong>{$this->new['name']}</strong> already exists, please flag it as an Alternate, Reprint or Promo with the NARP input"
            );
        }

        // ERROR: Trying to add a NARP > 0 but no basic print found!
        else if (empty($existing) && $this->new['narp'] !== '0') {
            throw new CrudException(
                "A basic print of card <strong>{$this->new['name']}</strong> DOES NOT exist, please flag this card as a Basic print or create a Basic print with the same name first"
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

        // User entered a custom total cost that overrides any calculation
        if (
            isset($this->state['total-cost']) &&
            $this->state['total-cost'] !== '-1'
        ) {
            $this->new['total_cost'] = intval($this->state['total-cost']);
            return;
        }

        if (isset($this->state['attribute-cost'])) {
            $totalCost += strlen($this->state['attribute-cost']);
        }

        if (isset($this->state['free-cost'])) {
            $totalCost += intval($this->state['free-cost']);
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
        if (isset($this->new['code']) && $this->new['code'] !== '') return;

        // Ex.: "NDR-001"
        $set = strtoupper($this->state['set-code']);
        $num = $this->state['number-padded'];
        $code = "{$set}-{$num}";

        // Ex.: "NDR-001 U"
        if ($this->new['rarity'] !== null) {
            $rarity = strtoupper($this->new['rarity']);
            // $code .= " {$rarity}"; // Old code with whitespace
            $code .= $rarity; // No whitespace!
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
        $set = (new GameSet)->byId($this->state['set-id'], ['clusters_id']);
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
        ($this->new['layout'] !== '0')
            ? $suffix = lookup("layouts.id2code.{$this->new['layout']}")
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

        // On updating...
        if (empty($this->old)) return;

        // Check if there's a new image
        $image = &$this->input['image'];
        $imageChanged = isset($image) && $image['error'] === UPLOAD_ERR_OK;

        // Check if image paths have changed
        $oldThumbPath = Uri::removeQueryString($this->old['thumb_path']);
        $pathsChanged = $oldThumbPath !== $this->new['thumb_path'];

        // Set some flags as "extra props" (starting with underscore)
        $this->new['_image-changed'] = $imageChanged ? 1 : 0;
        $this->new['_paths-changed'] = $pathsChanged ? 1 : 0;

        // New image, same path
        if ($imageChanged && !$pathsChanged) {
            $queryString = '?' . time();
            $this->new['image_path'] .= $queryString;
            $this->new['thumb_path'] .= $queryString;    
        }
    }

    /**
     * Calculates the legality for this card
     *
     * @return void
     */
    private function calculateLegality(): void
    {
        $formats = ViewCard::formatsListByCluster($this->new['clusters_id']);
        $formatCodes = array_column($formats, "code");
        $bitmask = new BitmaskFlags();
        $bitmask->setFlagsMap(lookup("formats.code2bit"));
        $bitmask->addFlags($formatCodes);
        $this->new['legality_bit'] = $bitmask->getMask();
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
        $bitmask = (new Bitmask)->setMask($this->new['type_bit']);

        // Remove costs
        foreach ($removables['no-cost'] as $type) {
            if (!$bitmask->hasBitValue($type)) continue;
            $this->new['attribute_cost'] = null;
            $this->new['free_cost'] = null;
            $this->new['total_cost'] = null;
            break;
        }

        // Remove attribute
        foreach ($removables['no-attribute'] as $type) {
            if (!$bitmask->hasBitValue($type)) continue;
            $this->new['attribute_bit'] = 0;
            break;
        }

        // Remove divinity
        $removeDivinity = true;
        foreach ($removables['can-divinity'] as $type) {
            if ($bitmask->hasBitValue($type)) $removeDivinity = false;
        }
        if ($removeDivinity) {
            $this->new['divinity'] = null;
        }

        // Remove ATK and DEF
        $removeBattleStats = true;
        foreach ($removables['can-battle'] as $type) {
            if ($bitmask->hasBitValue($type)) $removeBattleStats = false;
        }
        if ($removeBattleStats) {
            $this->new['atk'] = null;
            $this->new['def'] = null;
        }

        // Block any resonator without ATK and DEF values!
        if (
            $bitmask->hasBitValue(lookup('types.display.Resonator')) &&
            !isset($this->new['atk']) &&
            !isset($this->new['def'])
        ) {
            throw new CrudException(
                'A card with type <strong>"Resonator"</strong> '.
                'must have ATK and DEF values'
            );
        }
    }
}
