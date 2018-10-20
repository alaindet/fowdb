<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Input;
use App\Utils\Logger;

class TestController extends Controller
{
    // public function testTwig()
    // {
    //     return (new \App\Views\Page)
    //         ->setVariables([
    //             'name' => 'Alain',
    //             'age' => 28,
    //         ])
    //         ->render('test/twig/foo.html');
    // }

    public function publicMenu()
    {
        return view(
            'Public Menu',
            'src/resources/views/test/public-menu.php'
        );
    }

    public function publicStaticForm()
    {
        return view(
            'Public Static Route',
            'src/resources/views/test/public-static.php'
        );
    }

    public function publicStaticProcess(Input $input)
    {
        $data = $input->post();
        return Logger::html($data, '$_POST');
    }

    public function publicRouteParams(Input $input, $id, $hash)
    {
        $data = [ 'id' => $id, 'hash' => $hash ];
        return Logger::html($data, 'params');
    }

    public function adminMenu()
    {
        return view(
            'Admin Menu',
            'src/resources/views/test/admin-menu.php'
        );
    }

    public function adminStaticForm()
    {
        return view(
            'Public Static Route',
            'src/resources/views/test/admin-static.php'
        );
    }

    public function adminStaticProcess(Input $input)
    {
        $data = $input->post();
        return Logger::html($data, '$_POST');
    }

    public function adminRouteParams(Input $input, $id, $hash)
    {
        $process = \App\Utils\Strings::kebabToPascal($hash);

        $data = [
            'id' => $id,
            'hash' => $hash,
            'kebab-case' => $process
        ];
        return Logger::html($data, 'params');
    }
}
