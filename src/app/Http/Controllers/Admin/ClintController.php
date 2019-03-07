<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Views\Page;
use App\Services\FileSystem\FileSystem;

class ClintController extends Controller
{
    private $commands = [
        'cache-config' => [
            'label' => 'Cache the configuration',
            'name' => 'config:cache'
        ],
        'cache-clear' => [
            'label' => 'Clear the configuration file',
            'name' => 'config:clear'
        ],
        'env-to-production' => [
            'label' => 'Switch environment to production',
            'name' => 'env:switch',
            'arguments' => ['production']
        ],
        'env-to-development' => [
            'label' => 'Switch environment to development',
            'name' => 'env:switch',
            'arguments' => ['development']
        ],
        'lookup-cache' => [
            'label' => 'Cache lookup data',
            'name' => 'lookup:cache'
        ],
        'sitemap-make' => [
            'label' => 'Regenerate sitemap.xml',
            'name' => 'sitemap:make'
        ],
        'timestamp-js' => [
            'label' => 'Update Timestamp: JS',
            'name' => 'config:timestamp',
            'arguments' => ['js']
        ],
        'timestamp-css' => [
            'label' => 'Update Timestamp: CSS',
            'name' => 'config:timestamp',
            'arguments' => ['css']
        ],
        'timestamp-img' => [
            'label' => 'Update Timestamp: Image',
            'name' => 'config:timestamp',
            'arguments' => ['img']
        ],
        'timestamp-generic' => [
            'label' => 'Update Timestamp: Generic',
            'name' => 'config:timestamp',
            'arguments' => ['generic']
        ],
        'cards-sort' => [
            'label' => 'Regenerate all cards \'sorted_id\'',
            'name' => 'cards:sort',
        ],
    ];
    
    public function showForm(): string
    {
        return (new Page)
            ->template('pages/admin/clint/index')
            ->title('Clint commands')
            ->variables([
                'commands' => $this->commands
            ])
            ->render();
    }

    public function executeCommand(Request $request, $command)
    {
        $info = $this->commands[$command];
        $classes = FileSystem::loadFile(path_data('app/clint.php'));
        $class = $classes[$info['name']];
        $command = new $class();
        $command->run($info['options'] ?? [], $info['arguments'] ?? []);

        Alert::add($command->message(), 'info');
        Redirect::to('clint');
    }
}
