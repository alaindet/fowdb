<?php

namespace App\Services\Set;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Set\SetInputProcessor;
use App\Models\CardSet;
use App\Services\FileSystem;

class SetUpdateService extends CrudService
{
    public $inputProcessor = SetInputProcessor::class;
    public $model = CardSet::class;
    public $lookup = ['clusters', 'sets', 'spoilers'];

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
            ->table('sets')
            ->values($placeholders)
            ->where('id = :id');

        database()
            ->update($statement)
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $partialOld = $this->old['clusters_id'].'/'.$this->old['code'];
        $partialNew = $this->new['clusters_id'].'/'.$this->new['code'];

        if ($partialOld !== $partialNew) {

            $paths = [
                'images' => [
                    'old' => path_root('images/cards/'.$partialOld),
                    'new' => path_root('images/cards/'.$partialNew)
                ],
                'thumbnails' => [
                    'old' => path_root('images/thumbs/'.$partialOld),
                    'new' => path_root('images/thumbs/'.$partialNew)
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
        $message = collapse(
            "Set <strong> ",
            "#{$this->old['id']} ",
            "{$this->old['name']} ({$this->old['code']})",
            "</strong> updated to <strong>",
            "#{$this->old['id']} ",
            "{$this->new['name']} ({$this->new['code']})
            </strong>."
        );

        $uri = url('sets/manage');

        return [$message, $uri];
    }
}
