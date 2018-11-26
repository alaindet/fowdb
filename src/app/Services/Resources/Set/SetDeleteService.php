<?php

namespace App\Services\Resources\Set;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Resources\Set\SetInputProcessor;
use App\Models\CardSet;
use App\Services\FileSystem;

class SetDeleteService extends CrudService
{
    public $model = CardSet::class;
    public $lookup = ['clusters', 'sets', 'spoilers'];

    public function syncDatabase(): CrudServiceInterface
    {
        database()
            ->delete(
                statement('delete')
                    ->table('sets')
                    ->where('id = :id')
            )
            ->bind([':id' => $this->old['id']])
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $partial = $this->old['clusters_id'] . '/' . $this->old['code'];
        $cardsDirectory =  path_root('images/cards/'  . $partial);
        $thumbsDirectory = path_root('images/thumbs/' . $partial);

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
            "Set <strong> ",
            "#{$this->old['id']} ",
            "{$this->old['name']} ({$this->old['code']})",
            "</strong> deleted."
        );

        $uri = url('sets/manage');

        return [$message, $uri];
    }
}
