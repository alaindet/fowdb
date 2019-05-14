<?php

namespace App\Http\Controllers\Auth;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page\Page;
use App\Legacy\Authentication;
use App\Services\Alert;
use App\Legacy\Authorization;
use App\Http\Response\Redirect;

class LoginController extends Controller
{
    public function loginForm()
    {
        // Redirect to the appropriate user"s profile
        if ((Authorization::getInstance())->isLogged()) {
            Redirect::to("profile");
        }

        return (new Page)
            ->template("pages/auth/login")
            ->title("Login")
            ->render();
    }

    public function login(Request $request)
    {
        $request->validate([
            "username" => ["required","is:text"],
            "password" => ["required","is:text"],
        ]);

        $post = $request->getInput()->post;
        $username = $post->username;
        $password = $post->password;

        Authentication::login($username, $password);
        Alert::add("You signed in", "success");
        Redirect::to("profile");
    }

    public function logout()
    {
        Authentication::logout();
        Alert::add("You signed out", "warning");
        Redirect::to("/");
    }
}
