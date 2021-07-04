<?php

namespace App\Legacy\Router;

use App\Http\Request\Input;

trait PublicPagesTrait
{
    public function buildSearchFormPage(): void
    {
        redirect('cards/search');
    }

    public function buildSearchPage(): void
    {
        $get = Input::getInstance()->get();
        unset($get['do']);

        redirect('cards', $get);
    }

    public function buildCardPage(): void
    {
        $code = Input::getInstance()->get('code');

        redirect('card/'.$code);
    }

    public function buildSpoilerPage(): void
    {
        redirect('spoiler');
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
