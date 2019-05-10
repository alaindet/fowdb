<?php

namespace App\Http\Controllers\Auth;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Legacy\Authentication;

class LoginController extends Controller
{
    public function loginForm()
    {
        // Redirect to the appropriate user"s profile
        if (fd_auth()->logged()) fd_redirect("profile");

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

        $username = $request->input()->post("username", $escape = true);
        $password = $request->input()->post("password");

        Authentication::login($username, $password);
        fd_alert("You signed in", "success");
        fd_redirect("profile");
    }

    public function logout()
    {
        Authentication::logout();
        fd_alert("You signed out", "warning");
        fd_redirect("/");
    }
}
