<?php

namespace App\Legacy\Router;

use App\Http\Request\Input;

trait PublicPagesTrait
{
    public function buildSearchPage(): void
    {
        echo view_old(
            $title = 'Search',
            $template = 'old/search/search.php',
            $options = ['lightbox' => true, 'js' => [ 'public/search' ] ],
            $variables = ['thereWereResults' => false]
        );
    }

    public function buildSearchResultsPage(): void
    {
        require path_root('old/search/search_retrievedb.php');
    }

    public function buildCardPage(): void
    {
        require path_root('/old/cardpage/cardpage.php');
    }

    public function buildSpoilerPage(): void
    {
        echo view_old(
            $title = 'Spoiler',
            $template = 'old/spoiler/spoiler.php',
            $options = [
                'lightbox' => true,
                'js' => [
                    'public/search',
                    'public/spoiler'
                ]
            ]
        );
    }

    public function buildBanPage(): void
    {
        redirect('banlist');
    }

    public function buildCrPage(): void
    {
        $version = Input::getInstance()->get('v');

        isset($version)
            ? redirect('cr/'.$version)
            : redirect('cr');
    }

    public function buildErrataPage(): void
    {
        redirect('errata');
    }

    public function buildFormatsPage(): void
    {
        redirect('formats');
    }

    public function buildRacesPage(): void
    {
        redirect('races');
    }
}
