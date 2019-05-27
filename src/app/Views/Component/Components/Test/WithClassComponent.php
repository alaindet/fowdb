<?php

namespace App\Views\Component\Components\Test;

use App\Views\Component\AbstractComponent;

/**
 * Input
 * $a
 * $b
 * $c
 * 
 * Template vars
 * $aa
 * $bb
 */
class WithClassComponent extends AbstractComponent
{
    public $filename = "test/with-class";

    protected function process(): void
    {
        $this->templateVars = (object) [
            "aa" => $this->state->a * 2,
            "bb" => $this->state->b * 2,
        ];
    }
}
