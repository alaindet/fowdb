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
        // Redirect to the appropriate user's profile
        if (auth()->logged()) redirect('profile');

        return (new Page)
            ->template('pages/auth/login')
            ->title('Login')
            ->render();
    }

    public function login(Request $request)
    {
        $request->validate('post', [
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $username = $request->input()->post('username', $escape = true);
        $password = $request->input()->post('password');

        Authentication::login($username, $password);

        alert('You signed in', 'success');
        redirect('profile');
    }

    public function logout()
    {
        Authentication::logout();
        alert('You signed out', 'warning');
        redirect('/');
    }
}
