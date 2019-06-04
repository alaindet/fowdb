<?php

namespace App\Views\Component\Components\Navigation;

use App\Views\Component\AbstractComponent;
use App\Services\Configuration\Configuration;

/**
 * INPUT
 * [
 *   label => relative_url,
 *   ...,
 * ]
 * 
 * TEMPLATE VARIABLES
 * links [label => absolute_url] array
 */
class Breadcrumb extends AbstractComponent
{
    public $filename = "navigation/breadcrumb";

    protected function process(): void
    {
        $baseUrl = Configuration::getInstance()->get("app.url");
        $this->templateVars->links = [];

        foreach ($this->input as $label => $relativeUrl) {
            $url = ($relativeUrl === "#") ? "#" : "{$baseUrl}/{$relativeUrl}";
            $this->templateVars->links[$label] = $url;
        }
    }
}
