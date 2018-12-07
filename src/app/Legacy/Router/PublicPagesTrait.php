<?php

namespace App\Legacy\Router;

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
        echo view_old('Banlist', 'old/resources/ban/index.php');
    }

    public function buildCrPage(): void
    {
        require path_root('old/resources/cr/index.php');
    }

    public function buildErrataPage(): void
    {
        echo view_old('Errata', 'old/resources/errata/errata.php');
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
