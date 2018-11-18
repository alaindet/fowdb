<?php

namespace App\Services\Card;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\Card;
use App\Services\Card\CardInputProcessor;
use Intervention\Image\ImageManager;
use App\Services\Filesystem;
use App\Utils\Arrays;
use App\Utils\Uri;

class CardUpdateService extends CrudService
{
    protected $inputProcessor = CardInputProcessor::class;
    protected $model = Card::class; 

    public function syncDatabase(): CrudServiceInterface
    {
        $placeholders = [];
        $bind = [':id' => $this->old['id']];

        foreach (array_keys($this->new) as $key) {
            $placeholder = ":{$key}";
            $placeholders[$key] = $placeholder;
            $bind[$placeholder] = $this->new[$key];
        }

        // Create a new card entity on the database
        database()
            ->update(
                statement('update')
                    ->table('cards')
                    ->values($placeholders)
                    ->where('id = :id')
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $image = $this->inputProcessorInstance->getInput('image');

        // No image passed
        if (!isset($image) || $image['error'] !== UPLOAD_ERR_OK) return $this;

        // Absolute paths
        $paths = Arrays::map([
            'old-image' => $this->old['image_path'],
            'old-thumb' => $this->old['thumb_path'],
            'new-image' => $this->new['image_path'],
            'new-thumb' => $this->new['thumb_path']
        ], function ($path) {
            return path_root(Uri::removeQueryString($path));
        });

        // Remove old cards
        FileSystem::deleteFile($paths['old-image']);
        FileSystem::deleteFile($paths['old-thumb']);

        // Append a querystring to images to bust the cache
        $queryString = '?' . time();
        $this->new['image_path'] .= $queryString;
        $this->new['thumb_path'] .= $queryString;

        // Store HQ image (apply watermark)
        (new ImageManager)
            ->make($image['tmp_name'])
            ->resize(480, 670)
            ->insert(path_root('images/watermark/watermark480.png'))
            ->save($paths['new-image'], 80);

        // Store LQ image (apply watermark)
        (new ImageManager)
            ->make($image['tmp_name'])
            ->resize(280, 391)
            ->insert(path_root('images/watermark/watermark280.png'))
            ->save($paths['new-thumb'], 80);

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        $link = url_old('card', ['code' => urlencode($this->new['code'])]);

        $message = collapse(
            "Card ",
            "<strong>",
                "<a href=\"{$link}\">",
                    "{$this->new['name']} ({$this->new['code']})",
                "</a>",
            "</strong> ",
            "updated."
        );

        $uri = $link;

        return [$message, $uri];
    }
}
