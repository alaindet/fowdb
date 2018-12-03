<?php

namespace App\Legacy\Router;

trait AdminPagesTrait
{
    public function buildTrimImagePage(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'GET') {
            echo view_old('Admin:Trim image', 'old/admin/image-trim/form.php');
        } elseif ($method === 'POST') {
            require path_root('old/admin/image-trim/process.php');
        }
    }

    public function buildClintPage(): void
    {
        echo view_old('Admin:Clint', 'old/admin/clint/index.php');
    }

    public function buildLookupPage(): void
    {
        echo view_old(
            'Admin:Lookup',
            'old/admin/lookup/index.php',
            null, null, $minimize = false
        );   
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
