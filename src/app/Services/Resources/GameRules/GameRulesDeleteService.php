<?php

namespace App\Services\Resources\GameRules;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\GameRules as Model;
use App\Services\FileSystem\FileSystem;

class GameRulesDeleteService extends CrudService
{
    protected $model = Model::class; 

    public function syncDatabase(): CrudServiceInterface
    {
        $bind = [':id' => $this->old['id']];

        fd_database()
            ->delete(
                fd_statement('delete')
                    ->table('game_rules')
                    ->where('id = :id')
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $paths = [
            'src' => $this->old['*source_path'],
            'doc' => $this->old['*doc_path'],
        ];

        foreach ($paths as $path) {
            FileSystem::deleteFile($path);
        }

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        $message = (
            'Comprehensive rules <strong>'.
            "ver. {$this->old['version']} ({$this->old['date_validity']})".
            '</strong> deleted.'
        );

        $uri = url('cr/manage');

        return [$message, $uri];
    }
}
