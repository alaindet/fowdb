<?php

namespace App\Legacy\Router;

trait AdminPagesTrait
{
    public function buildClintPage(): void
    {
        echo view_old('Admin:Clint', 'old/admin/clint/index.php');
    }

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

    public function buildHashPage(): void
    {
        echo view_old('Admin:Hash', 'old/admin/hash/index.php');
    }
}
