<?php

namespace App\Legacy\Router;

use App\Http\Request\Input\InputManager;
use App\Http\Response\Redirect;

trait PublicPagesTrait
{
    public function buildSearchFormPage(): void
    {
        Redirect::to("cards/search");
    }

    public function buildSearchPage(): void
    {
        $get = InputManager::getInstance()->get;
        unset($get["do"]);

        Redirect::to("cards", $get);
    }

    public function buildCardPage(): void
    {
        $code = Input::getInstance()->get("code");

        Redirect::to("card/".$code);
    }

    public function buildSpoilerPage(): void
    {
        Redirect::to("spoiler");
    }

    public function buildBanPage(): void
    {
        Redirect::to("banlist");
    }

    public function buildCrPage(): void
    {
        $version = Input::getInstance()->get("v");

        isset($version)
            ? Redirect::to("cr/".$version)
            : Redirect::to("cr");
    }

    public function buildErrataPage(): void
    {
        Redirect::to("errata");
    }

    public function buildFormatsPage(): void
    {
        Redirect::to("formats");
    }

    public function buildRacesPage(): void
    {
        Redirect::to("races");
    }
}
