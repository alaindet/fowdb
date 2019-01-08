<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

class ComponentsController extends Controller
{
    public function buttonCheckbox(Request $request): string
    {
        return (new Page)
            ->template('test/button-checkbox')
            ->title('Test: button-checkbox component')
            ->minify(false)
            ->render();
    }
    
    public function buttonCheckboxes(Request $request): string
    {
        return (new Page)
            ->template('test/button-checkboxes')
            ->title('Test: button-checkboxes component')
            ->minify(false)
            ->render();
    }

    public function inputDropdown(Request $request): string
    {
        return (new Page)
            ->template('test/input-dropdown')
            ->title('Test: input-dropdown component')
            ->options(['scripts' => ['test/input-dropdown']])
            ->minify(false)
            ->render();
    }

    public function buttonDropdown(Request $request): string
    {
        return (new Page)
            ->template('test/button-dropdown')
            ->title('Test: button-dropdown component')
            ->options(['scripts' => ['test/button-dropdown']])
            ->minify(false)
            ->render();
    }

    public function selectMultiple(Request $request): string
    {
        return (new Page)
            ->template('test/select-multiple')
            ->title('Test: select-multiple component')
            ->options(['scripts' => ['test/select-multiple']])
            ->minify(false)
            ->render();
    }
}
