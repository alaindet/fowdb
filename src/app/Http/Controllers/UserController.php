<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Views\Page\Page;
use App\Legacy\Authorization;
use App\Http\Response\Redirect;

class UserController extends Controller
{
    public function showProfile()
    {
        $auth = Authorization::getInstance();
        $level = $auth->level();

        if ($level === Authorization::ROLE_ADMIN) {
            Redirect::to("admin");
        }

        if ($level === Authorization::ROLE_JUDGE) {
            Redirect::to("judge");
        }
    }

    public function adminShowProfile(): string
    {
        return (new Page)
            ->template("pages/admin/profile")
            ->title("Admin Profile")
            ->variables([
                "links" => $this->menuLinks(["judge", "admin"])
            ])
            ->render();
    }

    public function judgeShowProfile(): string
    {
        return (new Page)
            ->template("pages/judge/profile")
            ->title("Judge Profile")
            ->variables([
                "links" => $this->menuLinks(["judge"])
            ])
            ->render();
    }

    private function menuLinks(array $roles): array
    {
        $links = [
            "judge" => [
                fd_url("cards/manage") => "Game: Cards",
                fd_url("sets/manage") => "Game: Sets",
                fd_url("clusters/manage") => "Game: Clusters",
                fd_url("formats/manage") => "Game: Formats",
                fd_url("rulings/manage") => "Game: Rulings",
                fd_url("restrictions/manage") => "Play: Banned and Limited cards",
                fd_url("cr/manage") => "Play: Comprehensive Rules",
                fd_url("images/trim") => "Tool: Trim an image",
            ],
            "admin" => [
                fd_url("artists") => "Tool: Artists",
                fd_url("lookup") => "Admin: Lookup data",
                fd_url("clint") => "Admin: Clint commands",
                fd_url("hash") => "Admin: Hash a string",
                fd_url("phpinfo") => "Admin: PHP info",
            ],
        ];

        $result = [];

        foreach ($roles as $role) {
            $result = array_merge($result, $links[$role]);
        }

        return $result;
    }
}
