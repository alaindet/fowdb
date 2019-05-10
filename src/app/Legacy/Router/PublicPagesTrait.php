<?php

namespace App\Legacy\Router;

use App\Http\Request\Input;

trait PublicPagesTrait
{
    public function buildSearchFormPage(): void
    {
        fd_redirect("cards/search");
    }

    public function buildSearchPage(): void
    {
        $get = Input::getInstance()->get();
        unset($get["do"]);

        fd_redirect("cards", $get);
    }

    public function buildCardPage(): void
    {
        $code = Input::getInstance()->get("code");

        fd_redirect("card/".$code);
    }

    public function buildSpoilerPage(): void
    {
        fd_redirect("spoiler");
    }

    public function buildBanPage(): void
    {
        fd_redirect("banlist");
    }

    public function buildCrPage(): void
    {
        $version = Input::getInstance()->get("v");

        isset($version)
            ? fd_redirect("cr/".$version)
            : fd_redirect("cr");
    }

    public function buildErrataPage(): void
    {
        fd_redirect("errata");
    }

    public function buildFormatsPage(): void
    {
        fd_redirect("formats");
    }

    public function buildRacesPage(): void
    {
        fd_redirect("races");
    }
}
