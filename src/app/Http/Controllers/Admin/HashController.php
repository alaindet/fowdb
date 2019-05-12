<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

class HashController extends Controller
{
    public function hashForm(): string
    {
        return (new Page)
            ->template('pages/admin/hash/index')
            ->title('Hash a string')
            ->variables([
                'breadcrumbs' => [
                    'Admin' => fd_url('profile'),
                    'Hash' => '#'
                ]
            ])
            ->render();
    }

    public function hash(Request $request)
    {
        $input = $request->input()->post('to-hash');
        $output = password_hash($input, PASSWORD_BCRYPT);

        return (new Page)
            ->template('pages/admin/hash/index')
            ->title('Hash a string')
            ->variables([
                'breadcrumbs' => [
                    'Admin' => fd_url('profile'),
                    'Hash' => fd_url('hash'),
                    'Result' => '#'
                ],
                'input' => $input,
                'output' => $output
            ])
            ->render();
    }
}
