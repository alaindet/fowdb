<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Cache\Cache;
use App\Utils\Logger;
use App\Utils\Paths;
use App\Services\FileSystem\FileSystem;

class CacheRoutesCommand extends Command
{
    public $name = 'cache:routes';

    public function run(array $options, array $arguments): void
    {
        $routesFile = Paths::inDataDir("app/routes.php");
        $routes = FileSystem::loadFile($routesFile);
        $cachedFile = Cache::store("routes.json", $routes);
        $this->message = "All routes were cached in {$cachedFile}";
    }
}
