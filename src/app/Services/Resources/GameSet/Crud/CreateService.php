<?php

namespace App\Services\Resources\GameSet\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\GameSet\Crud\InputProcessor;
use App\Services\FileSystem\FileSystem;
use App\Utils\Paths;

class CreateService extends CrudService
{
    public $inputProcessor = InputProcessor::class;
    
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

        fd_database()
            ->insert(
                fd_statement('insert')
                    ->table('game_sets')
                    ->values($placeholders)
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $partial = $this->new['clusters_id'] . '/' . $this->new['code'];
        $cardsDirectory =  Paths::inRootDir('images/cards/'  . $partial);
        $thumbsDirectory = Paths::inRootDir('images/thumbs/' . $partial);

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
        $message = (
            "New set <strong> ".
            "#{$this->new['id']} ".
            "{$this->new['name']} ({$this->new['code']})".
            "</strong> created."
        );

        $uri = fd_url('sets/manage');

        return [$message, $uri];
    }
}
