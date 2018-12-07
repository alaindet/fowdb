<?php

namespace App\Legacy\Router;

use App\Http\Request\Input;
use App\Legacy\Router\PublicPagesTrait;
use App\Legacy\Router\AdminPagesTrait;

class Router
{
    use PublicPagesTrait;
    use AdminPagesTrait;

    private $input;
    private $p = '';
    private $do = '';

    public function __construct()
    {
        $this->input = Input::getInstance();
        $this->p = $this->input->get('p');
        $this->do = $this->input->get('do');
    }

    public function run(): void
    {
        // Public pages
        if ($this->do === 'search') $this->buildSearchResultsPage();
        elseif ($this->p === 'search')            $this->buildSearchPage();
        elseif ($this->p === 'card')              $this->buildCardPage();
        elseif ($this->p === 'spoiler')           $this->buildSpoilerPage();
        
        // Redirects
        elseif ($this->p === 'resources/ban')     $this->buildBanPage();
        elseif ($this->p === 'resources/cr')      $this->buildCrPage();
        elseif ($this->p === 'resources/errata')  $this->buildErrataPage();
        elseif ($this->p === 'resources/formats') $this->buildFormatsPage();
        elseif ($this->p === 'resources/races')   $this->buildRacesPage();

        // Admin pages
        elseif ($this->p === 'admin/trimg-image') $this->buildTrimImagePage();
        elseif ($this->p === 'admin/clint')       $this->buildClintPage();
        elseif ($this->p === 'admin/hash')        $this->buildHashPage();

        // Temporary admin pages
        elseif ($this->p === 'admin/_artists/select-set') {
            $this->buildArtistSelectSetPage();
        }
        elseif ($this->p === 'admin/_artists/select-card') {
            $this->buildArtistSelectCardPage();
        }
        elseif ($this->p === 'admin/_artists/card') {
            $this->buildArtistCardPage();
        }
    }
}
