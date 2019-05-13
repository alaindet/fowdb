<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Services\Lookup\Lookup;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Http\Response\JsonResponse;
use App\Views\Page\Page;

class LookupController extends Controller
{
    public function index(Request $request): string
    {
        return (new Page)
            ->template('test/lookup/index')
            ->title('Lookup,Index')
            ->variables([
                'features' => Lookup::getInstance()->features()
            ])
            ->render();
    }

    public function readAll(Request $request): string
    {
        $data = Lookup::getInstance()->getAll();
        return (new JsonResponse)->setData($data)->render();
    }

    public function read(Request $request, string $feat): string
    {
        $lookup = Lookup::getInstance();
        if (!$lookup->exists($feat)) {
            Alert::set("Lookup data for feature \"{$feat}\" doesn't exist.");
            Redirect::to("test");
        }
        $data = $lookup->get($feat);
        return (new JsonResponse)->setData($data)->render();
    }

    public function buildAll(Request $request): string
    {
        $data = Lookup::getInstance()->generateAll()->store()->getAll();
        return (new JsonResponse)->setData($data)->render();
    }

    public function build(Request $request, string $feat): string
    {
        $lookup = Lookup::getInstance();
        if (!$lookup->exists($feat)) {
            Alert::set("Lookup data for feature \"{$feat}\" can't be built.");
            Redirect::to("test");
        }
        $data = $lookup->generate($feat)->store()->get($feat);
        return (new JsonResponse)->setData($data)->render();
    }
}
