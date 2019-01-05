<?php

namespace App\Views;

use App\Base\Base;
use App\Utils\Strings;

abstract class Component
{
    /**
     * This will hold any component's data to be processed and/or displayed
     *
     * @var array
     */
    protected $state = [];

    /**
     * Update the state via a callback
     * 
     * The callback gets the previous state array as the argument
     * The callback should return an associative array (the new state)
     * 
     * The new state is recursively merged with the previous state, so anything
     * existing on the state and not explicitly overwritten remains the same
     *
     * @param callable The callable returning the new state
     * @return Component
     */
    public function setState(callable $callback): Component
    {
        $newState = $callback($this->state);

        $this->state = array_replace_recursive($this->state, $newState);

        return $this;
    }

    /**
     * Loads the view file of a component and renders it by passing it data
     *
     * @param array $__variables
     * @return string Rendered component as HTML
     */
    protected function renderTemplate(array $__variables = []): string
    {
        // Shorter but slower (?)
        // return include_view("components/{$this->filename}", $__variables);

        // Declare scoped variables
        foreach ($__variables as $__name => $__value) {
            if (strpos($__name, '-')) {
                $__name = Strings::kebabToSnake($__name);
            }
            $$__name = $__value;
        }

        // Turn on the buffer to catch the rendered component,
        // Then return it as HTML string
        ob_start();
        include path_views("components/{$this->filename}.tpl.php");
        return ob_get_clean();
    }
}
