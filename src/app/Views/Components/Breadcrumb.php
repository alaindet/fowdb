<?php

namespace App\Views\Components;

use App\Views\Component;
use App\Services\Configuration\Configuration;

class Breadcrumb extends Component
{
    /**
     * Represents /src/resources/views/components/breadcrumb.tpl.php
     *
     * @var string
     */
    public $filename = "breadcrumb";

    /**
     * Turns links into absolute links based on URL stored in configuration
     *
     * @return Breadcrumb
     */
    private function absoluteLinks(): void
    {   
        $url = (Configuration::getInstance())->get("app.url");

        foreach ($this->state as $link) {
            if ($link !== "#") {
                $link = $url . $link;
            }
        }
    }

    /**
     * Renders the HTML
     *
     * @return string
     */
    public function render(): string
    {
        $this->absoluteLinks();
        
        return $this->renderTemplate(["links" => $this->state]);
    }
}
