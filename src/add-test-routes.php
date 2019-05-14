<?php

use App\Utils\Paths;
use App\Services\FileSystem\FileSystem;

// Grab test routes
$testRoutesPath = Paths::inDataDir("test/routes.php");
$testRoutes = FileSystem::loadFile($testRoutesPath);

// Merge test routes with existing ones
$accessLevels = [
    "public",
    "user",
    "admin",
    "judge"
];

foreach ($accessLevels as $level) {
    $routes[$level] = array_merge($routes[$level], $testRoutes[$level]);
}
