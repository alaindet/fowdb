<?php

namespace App\Views\Components;

use App\Views\Component;

class InputDropdown extends Component
{
    /**
     * Represents /src/resources/views/components/form/input-dropdown.tpl.php
     *
     * @var string
     */
    public $filename = 'form/input-dropdown';

    /**
     * Adds 'size' (lv 1) element to 'input' and 'dropdown' (lv 0) elements
     * to the state array
     *
     * @return void
     */
    private function processSizes(): void
    {
        // Default
        if (!isset($this->state['size'])) {
            $this->state['dropdown']['size'] = '';
            $this->state['input']['size'] = '';
            return;
        }

        // Large
        if ($this->state['size'] === 'lg') {
            $this->state['dropdown']['size'] = ' btn-lg';
            $this->state['input']['size'] = ' input-lg';
            return;
        }

        // Small
        if ($this->state['size'] === 'sm') {
            $this->state['dropdown']['size'] = ' btn-sm';
            $this->state['input']['size'] = ' input-sm';
        }
    }

    /**
     * Processes state for inner components
     *
     * @return void
     */
    private function processState(): void
    {
        $dp =& $this->state['dropdown'];

        // Default state
        $state = [
            'input' => '',
            'dropdown' => [
                'face' => $dp['default']['face'],
                'value' => $dp['default']['value']
            ],
        ];

        // Input
        if (isset($this->state['input']['state'])) {
            $state['input'] = $this->state['input']['state'];
        }

        // Dropdown
        if (isset($dp['state']) && isset($dp['items'][$dp['state']])) {
            $state['dropdown'] = [
                'face' => $dp['items'][$dp['state']],
                'value' => $dp['state']
            ];
        }

        // Alter initial state
        $this->state['input']['state'] = $state['input'];
        $this->state['dropdown']['state'] = $state['dropdown'];
    }

    /**
     * Renders the HTML
     *
     * @return string
     */
    public function render(): string
    {
        $this->processSizes();
        $this->processState();

        return $this->renderTemplate($this->state);
    }
}
