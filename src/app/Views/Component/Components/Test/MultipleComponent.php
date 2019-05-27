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
class MultipleComponent extends AbstractComponent
{
    public $filename = "test/multiple";

    protected function process(): void
    {
        $this->templateVars->bar = $this->input->foo * 2;
    }
}
