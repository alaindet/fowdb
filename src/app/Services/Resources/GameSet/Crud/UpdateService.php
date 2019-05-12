<?php

namespace App\Services\Resources\GameSet\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\GameSet\Crud\InputProcessor;
use App\Models\GameSet as Model;
use App\Services\FileSystem\FileSystem;

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

        $statement = fd_statement('update')
            ->table('game_sets')
            ->values($placeholders)
            ->where('id = :id');

        fd_database()
            ->update($statement)
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $partialOld = $this->old['clusters_id'].'/'.$this->old['code'];
        $partialNew = $this->new['clusters_id'].'/'.$this->new['code'];

        if ($partialOld !== $partialNew) {

            $paths = [
                'images' => [
                    'old' => fd_path_root('images/cards/'.$partialOld),
                    'new' => fd_path_root('images/cards/'.$partialNew)
                ],
                'thumbnails' => [
                    'old' => fd_path_root('images/thumbs/'.$partialOld),
                    'new' => fd_path_root('images/thumbs/'.$partialNew)
                ]
            ];

            foreach ($paths as $dirs) {
                FileSystem::renameDirectory($dirs['old'], $dirs['new']);
            }

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
            "Set <strong> ".
                "#{$this->old['id']} ".
                "{$this->old['name']} ({$this->old['code']})".
            "</strong> updated to <strong>".
                "#{$this->old['id']} ".
                "{$this->new['name']} ({$this->new['code']})".
            "</strong>."
        );

        $uri = url('sets/manage');

        return [$message, $uri];
    }
}
