<?php

namespace App\Services\Resources\Card;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Models\Card;
use App\Services\Resources\Card\CardInputProcessor;
use Intervention\Image\ImageManager;

class CardCreateService extends CrudService
{
    protected $inputProcessor = CardInputProcessor::class;
    protected $model = Card::class;

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

        // Create a new card entity on the database
        database()
            ->insert(
                statement('insert')
                    ->table('cards')
                    ->values($placeholders)
            )
            ->bind($bind)
            ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        $image = $this->inputProcessorInstance->getInput('image');

        // Create image
        (new ImageManager)
            ->make($image['tmp_name'])
            ->resize(480, 670)
            ->insert(path_root('images/watermark/watermark480.png'))
            ->save(path_root($this->new['image_path']), 80);

        // Create thumbnail image
        (new ImageManager)
            ->make($image['tmp_name'])
            ->resize(280, 391)
            ->insert(path_root('images/watermark/watermark280.png'))
            ->save(path_root($this->new['thumb_path']), 80);

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
            "New card ",
            "<strong>",
                "<a href=\"",
                    url_old('card', [
                        'code' => urlencode($this->new['code'])
                    ]),
                "\">",
                    "{$this->new['name']} ({$this->new['code']})",
                "</a>",
            "</strong> ",
            "created."
        );

        $uri = url('cards/create');

        return [$message, $uri];
    }
}