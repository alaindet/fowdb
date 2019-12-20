<?php

namespace App\Services\Resources\GameCluster\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\GameCluster\Crud\InputProcessor;
use App\Models\GameCluster as Model;

class UpdateService extends CrudService
{
    public $inputProcessor = InputProcessor::class;
    public $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [];
        foreach (array_keys($this->new) as $key) {
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];
            }
        }

        $statement = statement('update')
            ->table('game_clusters')
            ->values($placeholders)
            ->where('id = :id');

        database()
            ->update($statement)
            ->bind($bind)
            ->execute();

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        $old = "#{$this->old['id']} {$this->old['name']} ({$this->old['code']})";
        $new = "#{$this->old['id']} {$this->new['name']} ({$this->new['code']})";
        $message = "Cluster <strong>{$old}</strong> updated to <strong>{$new}</strong>.";

        return [$message];
    }
}
