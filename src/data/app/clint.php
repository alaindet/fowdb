<?php

/**
 * The list of all available Clint commands
 * 
 * NAME => CLASS
 */
return [

    'list' => \App\Clint\Commands\ListCommand::class,
    'help' => \App\Clint\Commands\HelpCommand::class,
    'clint:add' => \App\Clint\Commands\ClintAddCommand::class,
    'config:cache' => \App\Clint\Commands\ConfigurationCacheCommand::class,
    'config:clear' => \App\Clint\Commands\ConfigurationClearCommand::class,
    'env:get' => \App\Clint\Commands\GetEnvironmentCommand::class,
    'env:switch' => \App\Clint\Commands\SwitchEnvironmentCommand::class,
    'lookup:cache' => \App\Clint\Commands\LookupCacheCommand::class,
    'sitemap:make' => \App\Clint\Commands\SitemapMakeCommand::class,
    'config:timestamp' => \App\Clint\Commands\ConfigTimestampCommand::class,

];
