<?php

namespace App\Services\Resources\Set;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\Set\SetInputProcessor;
use App\Services\FileSystem;

class SetCreateService extends CrudService
{
    public $inputProcessor = SetInputProcessor::class;
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

        database()
            ->insert(
                statement('insert')
                    ->table('game_sets')
                    ->values($placeholders)
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $partial = $this->new['clusters_id'] . '/' . $this->new['code'];
        $cardsDirectory =  path_root('images/cards/'  . $partial);
        $thumbsDirectory = path_root('images/thumbs/' . $partial);

        FileSystem::createDirectory($cardsDirectory);
        FileSystem::createDirectory($thumbsDirectory);

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
            "New set <strong> ",
            "#{$this->new['id']} ",
            "{$this->new['name']} ({$this->new['code']})",
            "</strong> created."
        );

        $uri = url('sets/manage');

        return [$message, $uri];
    }
}
