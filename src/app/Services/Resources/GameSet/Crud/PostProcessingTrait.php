<?php

namespace App\Services\Resources\GameSet\Crud;

use App\Exceptions\CrudException;

/**
 * This trait manipulates data after all Cluster input processors executed
 * Accesses these properties: $old, $new, $state
 */
trait PostProcessingTrait
{
    /**
     * Overrides App\Base\InputProcessor::afterProcessing
     *
     * @return void
     */
    public function afterProcessing(): void
    {
        // On creating...
        if (empty($this->old)) {

            $this->checkExistingResourceOnCreate();
        }

        // On updating...
        else {
            
            $this->checkExistingResourceOnUpdate();

        }

        $this->forceIsSpoilerInput();
        $this->forceReleaseDateInput();
    }

    /**
     * Checks if another identical set already exists
     *
     * @return void
     */
    private function checkExistingResourceOnCreate(): void
    {
        $existing = database()
            ->select(
                statement('select')
                    ->from('game_sets')
                    ->where('id = :id', 'OR')
                    ->where('name = :name', 'OR')
                    ->where('code = :code', 'OR')
            )
            ->bind([
                ':id'   => $this->new['id'],
                ':name' => $this->new['name'],
                ':code' => $this->new['code']
            ])
            ->first();
        
        if (!empty($existing)) {
            throw new CrudException(
                "A set with ID <strong>{$this->new['id']}</strong>, ".
                "or name <strong>{$this->new['name']}</strong>, ".
                "or code <strong>{$this->new['code']}</strong> ".
                "already exists"
            );
        }
    }

    /**
     * Checks if another set (with different ID) already existing with
     * this name OR code. Remember: set IDs, names and codes MUST be unique
     *
     * @return void
     */
    private function checkExistingResourceOnUpdate(): void
    {
        $existing = database()
            ->select(
                statement('select')
                    ->from('game_sets')
                    ->where('NOT(id = :id)')
                    ->where([
                        'name = :name',
                        'code = :code'
                    ], 'OR', 'AND')
            )
            ->bind([
                ':id'   => $this->new['id'],
                ':name' => $this->new['name'],
                ':code' => $this->new['code']
            ])
            ->first();

        if (!empty($existing)) {
            throw new CrudException(
                "A set named <strong>{$this->new['name']}</strong>, ".
                "or with code <strong>{$this->new['code']}</strong> ".
                "already exists. Sets must have unique names and codes."
            );
        }
    }

    /**
     * Forces is-spoiler input if not passed (unchecked checkbox)
     *
     * @return void
     */
    private function forceIsSpoilerInput(): void
    {
        if (
            !isset($this->new['is_spoiler']) ||
            $this->new['is_spoiler'] === ''
        ) {
            $this->new['is_spoiler'] = 0;
        }
    }

    /**
     * Forces release-date input if no value is passed
     *
     * @return void
     */
    private function forceReleaseDateInput(): void
    {
        if (
            !isset($this->new['date_release']) ||
            $this->new['date_release'] === ''
        ) {
            $this->new['date_release'] = null;
        }
    }
}
