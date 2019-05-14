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
            ->template("test/button-checkbox")
            ->title("Test: button-checkbox component")
            ->minify(false)
            ->render();
    }
    
    public function buttonCheckboxes(Request $request): string
    {
        return (new Page)
            ->template("test/button-checkboxes")
            ->title("Test: button-checkboxes component")
            ->minify(false)
            ->render();
    }

    public function buttonDropdown(Request $request): string
    {
        return (new Page)
            ->template("test/button-dropdown")
            ->title("Test: button-dropdown component")
            ->options(["scripts" => ["test/button-dropdown"]])
            ->minify(false)
            ->render();
    }

    public function buttonRadio(Request $request): string
    {
        return (new Page)
            ->template("test/button-radio")
            ->title("Test: button-radio component")
            ->minify(false)
            ->render();
    }

    public function inputDropdown(Request $request): string
    {
        return (new Page)
            ->template("test/input-dropdown")
            ->title("Test: input-dropdown component")
            ->options(["scripts" => ["test/input-dropdown"]])
            ->minify(false)
            ->render();
    }

    public function selectMultiple(Request $request): string
    {
        return (new Page)
            ->template("test/select-multiple")
            ->title("Test: select-multiple component")
            ->options(["scripts" => ["test/select-multiple"]])
            ->minify(false)
            ->render();
    }

    public function pagination(Request $request): string
    {
        // Fake pagination data
        $pagination = (object) [
            "totalCount" => 3128,
            "count" => 2500-2476+1,
            "page" => 100,
            "perPage" => 25,
            "lastPage" => 126,
            "lowerBound" => 2476,
            "upperBound" => 2500,
            "link" => fd_url("test/components/pagination"),
            "hasMorePages" => 1,
            "hasAnyPagination" => 1,
        ];

        return (new Page)
            ->template("test/pagination")
            ->title("Test: pagination component")
            ->variables([
                "pagination" => $pagination,
            ])
            ->minify(false)
            ->render();
    }
}
