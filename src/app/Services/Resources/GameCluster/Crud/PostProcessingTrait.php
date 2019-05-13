<?php

namespace App\Services\Resources\GameCluster\Crud;

use App\Base\Crud\Exceptions\CrudException;

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
    }

    /**
     * Checks if another identical cluster already exists
     *
     * @return void
     */
    private function checkExistingResourceOnCreate(): void
    {
        $existing = fd_database()
            ->select(
                fd_statement('select')
                    ->from('game_clusters')
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
                "A cluster with ID <strong>{$this->new['id']}</strong>, ".
                "or name <strong>{$this->new['name']}</strong>, ".
                "or code <strong>{$this->new['code']}</strong> ".
                "already exists"
            );
        }
    }

    /**
     * Checks if another cluster (different ID) already existing with
     * this name OR code. Remember: cluster IDs, names and codes MUST be unique
     *
     * @return void
     */
    private function checkExistingResourceOnUpdate(): void
    {
        $existing = fd_database()
            ->select(
                fd_statement('select')
                    ->from('game_clusters')
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
                "A cluster named <strong>{$this->new['name']}</strong>, ".
                "or with code <strong>{$this->new['code']}</strong> ".
                "already exists. Clusters must have unique names and codes."
            );
        }
    }
}
