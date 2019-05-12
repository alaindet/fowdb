<?php

namespace App\Services\Resources\Card\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\Card as Model;
use App\Services\FileSystem\FileSystem;
use App\Utils\Uri;

class DeleteService extends CrudService
{
    protected $model = Model::class; 

    public function syncDatabase(): CrudServiceInterface
    {
        $bind = [':cardid' => $this->old['id']];

        // Remove from 'cards' table
        fd_database()
            ->delete(
                fd_statement('delete')
                    ->table('cards')
                    ->where('id = :cardid')
            )
            ->bind($bind)
            ->execute();

        // Regenerate cards.sorted_id
        Model::buildAllSortId();
        
        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        $paths = [
            $this->old['image_path'],
            $this->old['thumb_path']
        ];

        foreach ($paths as $path) {
            $absolutePath = fd_path_root(Uri::removeQueryString($path));
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
        $uri = fd_url('cards/manage');

        return [$message, $uri];
    }
}
