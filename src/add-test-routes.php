<?php

// Grab test routes
$testRoutesPath = \App\Utils\Paths::inDataDir("test/routes.php");
$testRoutes = \App\Services\FileSystem\FileSystem::loadFile($testRoutesPath);

// Merge test routes with existing ones
$accessLevels = [
    'public',
    'user',
    'admin',
    'judge'
];
foreach ($accessLevels as $level) {
    $routes[$level] = array_merge($routes[$level], $testRoutes[$level]);
}
