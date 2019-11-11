<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Clint\Clint;
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
            'command' => '$ php clint config:cache',
            'name' => 'config:cache',
        ],
        'cache-clear' => [
            'label' => 'Clear the configuration file',
            'command' => '$ php clint config:clear',
            'name' => 'config:clear',
        ],
        'env-to-production' => [
            'label' => 'Switch environment to production',
            'command' => '$ php clint env:switch production',
            'values' => ['production'],
            'name' => 'env:switch',
        ],
        'env-to-development' => [
            'label' => 'Switch environment to development',
            'command' => '$ php clint env:switch development',
            'values' => ['development'],
            'name' => 'env:switch',
        ],
        'lookup-cache' => [
            'label' => 'Cache lookup data',
            'command' => '$ php clint lookup:cache',
            'name' => 'lookup:cache',
        ],
        'sitemap-make' => [
            'label' => 'Regenerate sitemap.xml',
            'command' => '$ php clint sitemap:make',
            'name' => 'sitemap:make',
        ],
        'timestamp-js' => [
            'label' => 'Update Timestamp: JS',
            'command' => '$ php clint config:timestamp js',
            'values' => ['js'],
            'name' => 'config:timestamp',
        ],
        'timestamp-css' => [
            'label' => 'Update Timestamp: CSS',
            'command' => '$ php clint config:timestamp css',
            'values' => ['css'],
            'name' => 'config:timestamp',
        ],
        'timestamp-img' => [
            'label' => 'Update Timestamp: Image',
            'command' => '$ php clint config:timestamp img',
            'values' => ['img'],
            'name' => 'config:timestamp',
        ],
        'timestamp-generic' => [
            'label' => 'Update Timestamp: Generic',
            'command' => '$ php clint config:timestamp generic',
            'values' => ['generic'],
            'name' => 'config:timestamp',
        ],
        'timestamp-all' => [
            'label' => 'Update Timestamp: all timestamps',
            'command' => '$ php clint config:timestamp',
            'name' => 'config:timestamp',
        ],
        'cards-sort' => [
            'label' => 'Regenerate \'cards.sorted_id\'',
            'command' => '$ php clint cards:sort',
            'name' => 'cards:sort',
        ],
        'cards-paths' => [
            'label' => 'Regenerate \'cards.image_path\' and \'cards.thumb_path\'',
            'command' => '$ php clint cards:paths',
            'name' => 'cards:paths',
        ],
        'cards-legality' => [
            'label' => 'Regenerate \'cards.legality_bit\'',
            'command' => '$ php clint cards:legaity',
            'name' => 'cards:legality',
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

        $clint = new Clint;
        $clint->setInput([
            "name" => $info["name"],
            "values" => $info["values"] ?? [],
            "options" => $info["options"] ?? [],
        ]);
        $clint->run();
        
        Alert::add($clint->getMessage(), 'info');
        Redirect::to('clint');
    }
}
