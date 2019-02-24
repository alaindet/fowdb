<?php

namespace App\Services\Resources\Card\Crud;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\Card as Model;
use App\Services\Resources\Card\Crud\InputProcessor;
use Intervention\Image\ImageManager;
use App\Services\Filesystem;
use App\Utils\Arrays;
use App\Utils\Uri;

class UpdateService extends CrudService
{
    protected $inputProcessor = InputProcessor::class;
    protected $model = Model::class; 

    private $image;
    private $didImageChange = false;
    private $didPathsChange = false;

    private function bustCachedImages(): void
    {
        $this->image = $this->inputProcessorInstance->getInput('image');
        $this->didImageChange = $this->new['_image-changed'];
        $this->didPathsChange = $this->new['_paths-changed'];
    }

    public function syncDatabase(): CrudServiceInterface
    {
        // Bust the cache if a new image was uploaded
        $this->bustCachedImages();

        $placeholders = [];
        $bind = [':id' => $this->old['id']];

        foreach (array_keys($this->new) as $key) {
            // Avoid extra props (with keys like _wassup)
            if (substr($key, 0, 1) !== '_') {
                $placeholder = ":{$key}";
                $placeholders[$key] = $placeholder;
                $bind[$placeholder] = $this->new[$key];    
            }
        }

        database()
            ->update(
                statement('update')
                    ->table('cards')
                    ->values($placeholders)
                    ->where('id = :id')
            )
            ->bind($bind)
            ->execute();

        // Regenerate cards.sorted_id
        Model::buildAllSortId();

        return $this;
    }

    public function syncFileSystem(): CrudServiceInterface
    {
        // No new image, no path changed
        if (!$this->didImageChange && !$this->didPathsChange) return $this;

        // Absolute paths
        $paths = Arrays::map([
            'old-image' => $this->old['image_path'],
            'old-thumb' => $this->old['thumb_path'],
            'new-image' => $this->new['image_path'],
            'new-thumb' => $this->new['thumb_path']
        ], function ($path) {
            return path_root(Uri::removeQueryString($path));
        });

        // Update this card's image paths
        if ($this->didPathsChange === 1) {
            
            // Rename old images
            FileSystem::renameFile($paths['old-image'], $paths['new-image']);
            FileSystem::renameFile($paths['old-thumb'], $paths['new-thumb']);

        }

        // Store new images for this card
        elseif ($this->didImageChange === 1) {

            // Remove old images
            FileSystem::deleteFile($paths['old-image']);
            FileSystem::deleteFile($paths['old-thumb']);

            // Store HQ image (apply watermark)
            (new ImageManager)
                ->make($this->image['tmp_name'])
                ->resize(480, 670)
                ->insert(path_root('images/watermark/watermark480.png'))
                ->save($paths['new-image'], 80);

            // Store LQ image (apply watermark)
            (new ImageManager)
                ->make($this->image['tmp_name'])
                ->resize(280, 391)
                ->insert(path_root('images/watermark/watermark280.png'))
                ->save($paths['new-thumb'], 80);
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
        $link = $uri = url('card/'.urlencode($this->new['code']));

        $message = (
            "Card ".
            "<strong>".
                "<a href=\"{$link}\">".
                    "{$this->new['name']} ({$this->new['code']})".
                "</a>".
            "</strong> ".
            "updated"
        );

        $uri = $link;

        return [$message, $uri];
    }
}
