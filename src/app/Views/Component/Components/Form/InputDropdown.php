<?php

namespace App\Views\Component\Components\Form;

use App\Views\Component\AbstractComponent;

/**
 * INPUT
 * array dropdown {
 *   string name,
 *   ?string state,
 *   array items,
 *   ?array css,
 *   array default: {
 *     string label,
 *     string value
 *   }
 * }
 * array input {
 *   string name,
 *   ?string state,
 *   ?array css,
 *   ?string placeholder,
 *   ?bool autofocus
 * }
 * ?string size
 * ?array css
 * 
 * TEMPLATE VARIABLES
 * object dropdown {
 *   string name,
 *   string css,
 *   array items,
 *   string size,
 *   object state: {
 *     string label,
 *     string value
 *   },
 *   object default: {
 *     string label,
 *     string value
 *   }
 * }
 * object input {
 *   string name,
 *   string size,
 *   string state,
 *   string css
 * }
 * string css
 */
class InputDropdown extends AbstractComponent
{
    public $filename = "form/input-dropdown";

    protected function process(): void
    {
        $this->declareTemplateVars();
        $this->calculateStyle();
        $this->calculateState();
    }

    private function declareTemplateVars(): void
    {
        // Dropdown
        $this->templateVars->dropdown = (object) [
            "name" => $this->input->dropdown["name"],
            "items" => $this->input->dropdown["items"],
            "css" => "",
            "default" => (object) [
                "label" => $this->input->dropdown["default"]["label"],
                "value" => $this->input->dropdown["default"]["value"],
            ],
        ];

        // Input
        $this->templateVars->input = (object) [
            "name" => $this->input->input["name"],
            "css" => "",
            "state" => "",
            "placeholder" => null,
            "autofocus" => $this->input->input["autofocus"] ?? null,
        ];

        // General
        $this->templateVars->css = "";
    }

    private function calculateStyle(): void
    {
        // Size
        $size = $this->input->size ?? null;

        if ($size === "sm") {
            $this->templateVars->dropdown->css = " btn-sm";
            $this->templateVars->input->css = " input-sm";
        }
        elseif ($size === "lg") {
            $this->templateVars->dropdown->css = " btn-lg";
            $this->templateVars->input->css = " input-lg";
        }

        // Input placeholder
        if (isset($this->input->input["placeholder"])) {
            $escapedPlaceholder = (
                str_replace(" ", "&nbsp;", $this->input->input["placeholder"])
            );
            $this->templateVars->input->placeholder = $escapedPlaceholder;
        }

        // Additional css: container
        if (isset($this->input->css)) {
            $classes = " ".implode($this->input->css);
            $this->templateVars->css .= $classes;
        }

        // Additional css: dropdown
        if (isset($this->input->dropdown["css"])) {
            $classes = " ".implode($this->input->dropdown["css"]);
            $this->templateVars->dropdown->css .= $classes;
        }

        // Additional css: input
        if (isset($this->input->input["css"])) {
            $classes = " ".implode($this->input->input["css"]);
            $this->templateVars->input->css .= $classes;
        }
    }

    private function calculateState(): void
    {
        // Input
        if ($this->input->input["state"] !== null) {
            $this->templateVars->input->state = $this->input->input["state"];
        }
        
        // Dropdown
        $dd = &$this->input->dropdown;
        if ($dd["state"] !== null && isset($dd["items"][$dd["state"]])) {
            $this->templateVars->dropdown->state = (object) [
                "label" => $dd["items"][$dd["state"]],
                "value" => $dd["state"],
            ];
        } else {
            $this->templateVars->dropdown->state = (object) [
                "label" => $dd["default"]["label"],
                "value" => $dd["default"]["value"],
            ];
        }
    }
}
