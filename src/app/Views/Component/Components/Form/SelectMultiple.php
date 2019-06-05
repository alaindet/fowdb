<?php

namespace App\Views\Component\Components\Form;

use App\Views\Component\AbstractComponent;

/**
 * REQUIRES
 * frontend/js/dependencies/form/select-multiple.js
 * 
 * INPUT
 * string name
 * ?string|string[] state
 * array items
 * ?array css [?array handle=>, ?array select=>]
 * 
 * VARIABLES
 * string name
 * bool isMultiple
 * string|string[] state
 * array items
 * object handle {
 *   string target
 *   string css
 * }
 * object select {
 *   string id
 *   bool isGrouped
 *   string css
 * }
 */
class SelectMultiple extends AbstractComponent
{
    public $templateName = "form/select-multiple";

    protected function process(): void
    {
        $this->templateVars->items = $this->input->items;

        if ($this->input->state !== null) {
            $this->templateVars->isMultiple = is_array($this->input->state);
            $this->templateVars->state = $this->input->state;
            if (!is_array($this->templateVars->state)) {
                $this->templateVars->state = [$this->input->state];
            }
        } else {
            $this->templateVars->isMultiple = false;
            $this->templateVars->state = [];
        }

        $this->templateVars->name = $this->input->name;
        if ($this->templateVars->isMultiple) {
            $this->templateVars->name .= "[]";
        }

        $this->templateVars->handle = new \stdClass();
        $this->templateVars->handle->target = "#fd-fsm-{$this->input->name}";
        $this->templateVars->handle->css = "";

        $this->templateVars->select = new \stdClass();
        $this->templateVars->select->id = "fd-fsm-{$this->input->name}";
        $this->templateVars->select->isGrouped = is_array(
            $this->input->items[array_keys($this->input->items)[0]]
        );
        $this->templateVars->select->css = "";
        
        if (isset($this->input->css)) {
            if (isset($this->input->css["handle"])) {
                $classes = " ".implode(" ", $this->input->css["handle"]);
                $this->templateVars->handle->css = $classes;
            }
            if (isset($this->input->css["select"])) {
                $classes = " ".implode(" ", $this->input->css["select"]);
                $this->templateVars->select->css = $classes;
            }
        }

        if ($this->templateVars->isMultiple) {
            $this->templateVars->handle->css .= " active";
        }
    }
}
