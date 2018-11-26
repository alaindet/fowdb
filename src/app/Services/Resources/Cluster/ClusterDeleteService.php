<?php

namespace App\Services\Resources\Cluster;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\FileSystem;
use App\Models\CardCluster;

class ClusterDeleteService extends CrudService
{
    public $model = CardCluster::class;
    public $lookup = ['clusters', 'formats'];

    public function syncDatabase(): CrudServiceInterface
    {
        database()
            ->delete(
                statement('delete')
                    ->table('clusters')
                    ->where('id = :id')
            )
            ->bind([':id' => $this->old['id']])
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $cardsDirectory =  path_root('images/cards/'.$this->old['id']);
        $thumbsDirectory = path_root('images/thumbs/'.$this->old['id']);

        FileSystem::deleteDirectory($cardsDirectory);
        FileSystem::deleteDirectory($thumbsDirectory);

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
            "Cluster <strong> ",
            "#{$this->old['id']} ",
            "{$this->old['name']} ({$this->old['code']})",
            "</strong> deleted."
        );

        return [$message];
    }
}
