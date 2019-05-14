<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Models\GameRules as Model;
use App\Services\Alert;
use App\Services\Resources\GameRules\GameRulesCreateService;
use App\Services\Resources\GameRules\GameRulesDeleteService;
use App\Services\Resources\GameRules\GameRulesUpdateService;
use App\Views\Page\Page;
use App\Exceptions\ModelNotFoundException;
use App\Http\Response\PlainTextResponse;
use App\Services\Validation\Validation;

class GameRulesController extends Controller
{
    /**
     * Download source .txt file for given game rules ID
     *
     * @param Request $request
     * @param string $id
     * @return string
     */
    public function showFile(Request $request, $id): string
    {
        $item = (new Model)->byId($id, ["*source_path"]);

        // ERROR: Missing model
        if (empty($item)) {
            throw new ModelNotFoundException(
                "Comprehensive Rules with ID <strong>{$id}</strong>".
                "does not exist on FoWDB."
            );
        }

        // Return source text file
        return (new PlainTextResponse)
            ->setData(["path" => $item["*source_path"]])
            ->render();
    }

    public function index(Request $request): string
    {
        $database = fd_database()
            ->select(
                fd_statement("select")
                    ->select([
                        "id",
                        "date_created",
                        "date_validity",
                        "version",
                        "doc_path"
                    ])
                    ->from("game_rules")
                    ->orderBy("date_validity DESC, id DESC")
            )
            ->page($request->input()->get("page") ?? 1)
            ->paginationLink($request->getCurrentUrl());

        $items = $database->paginate();

        return (new Page)
            ->template("pages/admin/cr/index")
            ->title("Comprehensive Rules,Index")
            ->variables([
                // paginate() must be called before paginationInfo()
                "items" => $items,
                "pagination" => $database->paginationInfo()
            ])
            ->render();
    }

    public function createForm(Request $request): string
    {
        return (new Page)
            ->template("pages/admin/cr/create")
            ->title("Comprehensive Rules,Create")
            ->variables([
                "previous" => $request->input()->previous()
            ])
            ->render();
    }

    public function create(Request $request): void
    {
        // Input
        $input = array_merge(
            $request->input()->post(),
            $request->input()->files()
        );

        // Validation
        $validation = new Validation;
        $validation->setData($input);
        $validation->setRules([
            "txt-file" => ["required","is:file"],
            "version" => [
                "required",
                "!empty",
                "match:[0-9]+.[0-9]+[a-z]*",
                "!exists:game_rules,version"
            ],
            "date-validity" => ["required","is:date"],
            "is-default" => ["required:0","is:boolean"],
        ]);
        $validation->validate();

        $service = new GameRulesCreateService($input);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFileSystem();
        [$message, $uri] = $service->getFeedback();

        Alert::add($message, "info");
        Redirect::toAbsoluteUrl($uri);
    }

    public function updateForm(Request $request, string $id): string
    {
        $item = (new Model)->byId($id);

        return (new Page)
            ->template("pages/admin/cr/update")
            ->title("Comprehensive Rules,Update")
            ->variables([
                "previous" => $request->input()->previous(),
                "item" => $item,
            ])
            ->render();
    }

    public function update(Request $request, string $id): void
    {
        $input = array_merge(
            $request->input()->post(),
            $request->input()->files()
        );

        $validation = new Validation;
        $validation->setData($input);
        $validation->setRules([
            "txt-file" => ["optional","is:file"],
            "version" => ["required","!empty","match:[0-9]+.[0-9]+[a-z]*"],
            "date-validity" => ["required","is:date"],
            "is-default" => ["optional","is:boolean"]
        ]);
        $validation->validate();

        $service = new GameRulesUpdateService($input, $id, [
            "id",
            "date_created",
            "date_validity",
            "version",
            "doc_path",
            "*doc_path",
            "*source_path",
        ]);
        $service->processInput();
        $service->syncDatabase();
        $service->syncFileSystem();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, "info");
        Redirect::toAbsoluteUrl($uri);
    }

    public function deleteForm(Request $request, string $id): string
    {
        $item = (new Model)->byId($id);

        return (new Page)
            ->template("pages/admin/cr/delete")
            ->title("Comprehensive Rules,Delete")
            ->variables([
                "item" => $item,
            ])
            ->render();
    }

    public function delete(Request $request, string $id): void
    {
        $service = new GameRulesDeleteService(null, $id, [
            "id",
            "date_created",
            "date_validity",
            "version",
            "doc_path",
            "*doc_path",
            "*source_path",
        ]);
        $service->syncDatabase();
        $service->syncFileSystem();
        [$message, $uri] = $service->getFeedback();
        
        Alert::add($message, "info");
        Redirect::toAbsoluteUrl($uri);
    }
}
