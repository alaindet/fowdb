<?php

namespace App\Services\Resources\GameCluster\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\FileSystem\FileSystem;
use App\Models\GameCluster as Model;

class DeleteService extends CrudService
{
    public $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        fd_database()
            ->delete(
                fd_statement('delete')
                    ->table('game_clusters')
                    ->where('id = :id')
            )
            ->bind([':id' => $this->old['id']])
            ->execute();

        fd_database()->resetAutoIncrement('game_clusters');

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $cardsDirectory =  fd_path_root('images/cards/'.$this->old['id']);
        $thumbsDirectory = fd_path_root('images/thumbs/'.$this->old['id']);

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
        $message = (
            "Cluster <strong> ".
            "#{$this->old['id']} ".
            "{$this->old['name']} ({$this->old['code']})".
            "</strong> deleted."
        );

        return [$message];
    }
}
