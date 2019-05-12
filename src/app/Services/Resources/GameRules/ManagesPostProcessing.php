<?php

namespace App\Services\Resources\GameRules;

use App\Exceptions\CrudException;

/**
 * This trait manipulates data after all Cluster input processors executed
 * Accesses these properties: $old, $new, $state
 */
trait ManagesPostProcessing
{
    /**
     * Overrides App\Base\InputProcessor::afterProcessing
     *
     * @return void
     */
    public function afterProcessing(): void
    {
        $this->checkExistingResourceOnUpdate();
        $this->buildDocumentPath();
    }

    /**
     * Checks if another set (with different ID) already existing with
     * this name OR code. Remember: set IDs, names and codes MUST be unique
     *
     * @return void
     */
    private function checkExistingResourceOnUpdate(): void
    {
        if (empty($this->old)) return;

        $existing = database()
            ->select(
                fd_statement('select')
                    ->from('game_rules')
                    ->where('NOT(id = :id)')
                    ->where('version = :version')
            )
            ->bind([
                ':id' => $this->old['id'],
                ':version' => $this->new['version']
            ])
            ->first();

        if (!empty($existing)) {
            throw new CrudException(
                "A comprehensive rules document with version ".
                "<strong>{$this->new['version']}</strong> already exists. ".
                "Versions must be unique on all documents."
            );
        }
    }

    /**
     * Builds the file path to store into the database
     *
     * @return void
     */
    private function buildDocumentPath(): void
    {
        $this->new['doc_path'] = "documents/cr/{$this->new['version']}.html";
    }
}
