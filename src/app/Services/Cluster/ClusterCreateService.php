<?php

namespace App\Services\Cluster;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Cluster\ClusterInputProcessor;
use App\Services\FileSystem;

class ClusterCreateService extends CrudService
{
    public $inputProcessor = ClusterInputProcessor::class;
    public $lookup = ['clusters', 'formats'];

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

        // Create cluster entity on the database
        database()
            ->insert(
                statement('insert')
                    ->table('clusters')
                    ->values($placeholders)
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        // Create new folders
        $cardsDirectory =  path_root('images/cards/'.$this->new['id']);
        $thumbsDirectory = path_root('images/thumbs/'.$this->new['id']);

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
            "New cluster <strong> ",
            "#{$this->new['id']} ",
            "{$this->new['name']} ({$this->new['code']})",
            "</strong> created."
        );

        return [$message];
    }
}
