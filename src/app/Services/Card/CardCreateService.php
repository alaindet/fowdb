<?php

namespace App\Services\Card;

use App\Base\CrudService;
use App\Base\CrudServiceInterface;
use App\Services\Card\CardInputProcessor;
use App\Models\Card;

class CardCreateService extends CrudService
{
    public $inputProcessor = CardInputProcessor::class;

    public function syncDatabase(): CrudServiceInterface
    {
        // Create card entity on the database
        // database()
        //     ->insert(statement('insert')
        //         ->table('cards')
        //         ->values([
        //             // ...
        //         ])
        //     )
        //     ->bind([
        //         // ...
        //     ])
        //     ->execute();

        return $this;
    }

    public function syncFilesystem(): CrudServiceInterface
    {
        // Perform filesystem operations here...

        return $this;
    }

    /**
     * Returns the success message and the redirect URI
     *
     * @return string
     */
    public function getFeedback(): array
    {
        // Assemble success message here...

        $message = 'Card creation message here...';

        $uri = url('cards/create');

        return [$message, $uri];
    }
}
