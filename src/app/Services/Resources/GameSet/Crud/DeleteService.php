<?php

namespace App\Services\Resources\GameSet\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\GameSet as Model;
use App\Services\FileSystem\FileSystem;

class DeleteService extends CrudService
{
    public $model = Model::class;

    public function syncDatabase(): CrudServiceInterface
    {
        database()
            ->delete(
                statement('delete')
                    ->table('game_sets')
                    ->where('id = :id')
            )
            ->bind([':id' => $this->old['id']])
            ->execute();

        database()->resetAutoIncrement('game_sets');

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $partial = $this->old['clusters_id'] . '/' . $this->old['code'];
        $cardsDirectory =  path_public('images/cards/'  . $partial);
        $thumbsDirectory = path_public('images/thumbs/' . $partial);

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
            "Set <strong> ".
                "#{$this->old['id']} ".
                "{$this->old['name']} ({$this->old['code']})".
            "</strong> deleted."
        );

        $uri = url('sets/manage');

        return [$message, $uri];
    }
}
