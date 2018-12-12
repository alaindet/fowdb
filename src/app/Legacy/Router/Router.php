<?php

namespace App\Legacy\Router;

use App\Http\Request\Input;
use App\Legacy\Router\PublicPagesTrait;

class Router
{
    use PublicPagesTrait;

    private $input;
    private $p = '';
    private $do = '';

    public function __construct()
    {
        $this->input = Input::getInstance();
        $this->p = $this->input->get('p');
        $this->do = $this->input->get('do');
    }

    public function run(): bool
    {
        // Public pages (they all redirected to pretty URLs)
        if    ($this->do === 'search')            $this->buildSearchPage();
        elseif ($this->p === 'search')            $this->buildSearchFormPage();
        elseif ($this->p === 'card')              $this->buildCardPage();
        elseif ($this->p === 'spoiler')           $this->buildSpoilerPage();
        elseif ($this->p === 'resources/ban')     $this->buildBanPage();
        elseif ($this->p === 'resources/cr')      $this->buildCrPage();
        elseif ($this->p === 'resources/errata')  $this->buildErrataPage();
        elseif ($this->p === 'resources/formats') $this->buildFormatsPage();
        elseif ($this->p === 'resources/races')   $this->buildRacesPage();

        return true;
    }
}
