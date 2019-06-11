<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page\Page;

class ComponentsController extends Controller
{
    public function buttonCheckbox(Request $request): string
    {
        return (new Page)
            ->template("test/components/button-checkbox")
            ->title("Test: button-checkbox component")
            ->minify(false)
            ->render();
    }
    
    public function buttonCheckboxes(Request $request): string
    {
        return (new Page)
            ->template("test/components/button-checkboxes")
            ->title("Test: button-checkboxes component")
            ->minify(false)
            ->render();
    }

    public function buttonDropdown(Request $request): string
    {
        return (new Page)
            ->template("test/components/button-dropdown")
            ->title("Test: button-dropdown component")
            ->options(["scripts" => ["test/components/button-dropdown"]])
            ->minify(false)
            ->render();
    }

    public function buttonRadio(Request $request): string
    {
        return (new Page)
            ->template("test/components/button-radio")
            ->title("Test: button-radio component")
            ->minify(false)
            ->render();
    }

    public function inputDropdown(Request $request): string
    {
        return (new Page)
            ->template("test/components/input-dropdown")
            ->title("Test: input-dropdown component")
            ->options(["scripts" => ["test/components/input-dropdown"]])
            ->minify(false)
            ->render();
    }

    public function selectMultiple(Request $request): string
    {
        return (new Page)
            ->template("test/components/select-multiple")
            ->title("Test: select-multiple component")
            ->options(["scripts" => ["test/components/select-multiple"]])
            ->minify(false)
            ->render();
    }

    public function selectSubmit(Request $request): string
    {
        return (new Page)
            ->template("test/components/select-submit")
            ->title("Test: select-submit component")
            ->minify(false)
            ->render();
    }

    public function pagination(Request $request): string
    {
        // Fake pagination data
        $pagination = (object) [
            "totalCount" => 100,
            "count" => 10,
            "page" => 5,
            "perPage" => 10,
            "lastPage" => 10,
            "lowerBound" => 41,
            "upperBound" => 50,
            "link" => fd_url("test/components/navigation/pagination", $_GET),
            "hasMorePages" => 1,
            "hasAnyPagination" => 1,
        ];

        return (new Page)
            ->template("test/components/navigation/pagination")
            ->title("Test: pagination component")
            ->variables([
                "pagination" => $pagination,
            ])
            ->minify(false)
            ->render();
    }

    public function newComponent(Request $request): string
    {
        return (new Page)
            ->template("test/new-components/index")
            ->title("New components test")
            ->minify(false)
            ->render();
    }
}
