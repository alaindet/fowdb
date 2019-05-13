<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Models\GameRules as Model;
use App\Exceptions\ModelNotFoundException;

class GameRulesController extends Controller
{
    public function index(Request $request): string
    {
        $items = fd_database()
            ->select(
                fd_statement("select")
                    ->from("game_rules")
                    ->orderBy([
                        "date_validity DESC",
                        "id DESC"
                    ])
            )
            ->get();

        return (new Page)
            ->template("pages/public/cr/index")
            ->title("Comprehensive Rules")
            ->variables([
                "items" => $items
            ])
            ->render();
    }

    public function show(Request $request, string $version): string
    {
        $item = (new Model)->byVersion($version);

        // ERROR: Missing model
        if (empty($item)) {
            throw new ModelNotFoundException(
                "Comprehensive Rules with version ".
                "<strong>{$version}</strong> ".
                "does not exist on FoWDB."
            );
        }
        
        return (new Page)
            ->template("pages/public/cr/show")
            ->title("Comprehensive Rules v. {$version}")
            ->variables([
                "path" => Paths::inRootDir($item["doc_path"])
            ])
            ->options([ "scripts" => [ "public/game-rules" ] ])
            ->minify(false)
            ->render();
    }
}
