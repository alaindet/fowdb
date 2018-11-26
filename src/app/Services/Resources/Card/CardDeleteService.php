<?php

namespace App\Services\Resources\Card;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\Card;
use App\Services\Filesystem;
use App\Utils\Uri;

class CardDeleteService extends CrudService
{
    protected $model = Card::class; 

    public function syncDatabase(): CrudServiceInterface
    {
        $bind = [':cardid' => $this->old['id']];

        // Remove from 'cards' table
        database()
            ->delete(
                statement('delete')
                    ->table('cards')
                    ->where('id = :cardid')
            )
            ->bind($bind)
            ->execute();

        // Remove from 'bans' table
        database()
            ->delete(
                statement('delete')
                    ->table('bans')
                    ->where('cards_id = :cardid')
            )
            ->bind($bind)
            ->execute();

        // Remove from 'rulings' table
        database()
            ->delete(
                statement('delete')
                    ->table('rulings')
                    ->where('cards_id = :cardid')
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $paths = [
            $this->old['image_path'],
            $this->old['thumb_path']
        ];

        foreach ($paths as $path) {
            $absolutePath = path_root(Uri::removeQueryString($path));
            FileSystem::deleteFile($absolutePath);
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
        $label = "{$this->old['name']} ({$this->old['code']})";
        $message = "Card <strong>{$label}</strong> deleted.";
        $uri = url('cards/manage');

        return [$message, $uri];
    }
}
