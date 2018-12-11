<?php

namespace App\Legacy\Router;

trait AdminPagesTrait
{
    public function buildArtistSelectSetPage(): void
    {
        echo view_old(
            'Artist:Select a set',
            'old/admin/_artists/select-set.php'
        );
    }

    public function buildArtistSelectCardPage(): void
    {
        echo view_old(
            'Artist:Select a card',
            'old/admin/_artists/select-card.php'
        );
    }

    public function buildArtistCardPage(): void
    {
        echo view_old(
                'Artist:Enter name for card',
                'old/admin/_artists/card.php',
                [
                    'jqueryui' => true,
                    'lightbox' => true,
                    'js' => [ 'admin/_artists' ]
                ]
            );
    }
}
