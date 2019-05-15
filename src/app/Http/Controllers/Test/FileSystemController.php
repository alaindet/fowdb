<?php

namespace App\Http\Controllers\Test;

use App\Base\Controller;
use App\Http\Request\Request;

use App\Services\FileSystem\FileSystem;
use App\Utils\Paths;

class FileSystemController extends Controller
{
    public function renderWithVars(): string
    {
        $path = Paths::inTemplatesDir("test/render/render-with-vars.tpl.php");
        $vars = (object) [
            "a" => 10,
            "b" => 20,
            "c" => 30,
        ];
        $rendered = FileSystem::renderFile($path, $vars, true);
        return $this->escapeHtml($rendered);
    }

    public function renderWithObj(): string
    {
        $path = Paths::inTemplatesDir("test/render/render-with-obj.tpl.php");
        $vars = (object)[
            "a" => 10,
            "b" => 20,
            "c" => 30,
        ];
        $rendered = FileSystem::renderFile($path, $vars, false);
        return $this->escapeHtml($rendered);
    }

    private function escapeHtml(string $html): string
    {
        return str_replace(
            ["<", ">"],
            ["&lt;", "&gt;"],
            $html
        );
    }
}
