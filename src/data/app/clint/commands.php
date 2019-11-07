<?php

return [

    'list' => \App\Clint\Commands\ListCommand::class,
    'help' => \App\Clint\Commands\HelpCommand::class,
    'cards:sort' => \App\Clint\Commands\CardsSortCommand::class,
    'clint:add' => \App\Clint\Commands\ClintAddCommand::class,
    'config:cache' => \App\Clint\Commands\ConfigurationCacheCommand::class,
    'config:clear' => \App\Clint\Commands\ConfigurationClearCommand::class,
    'config:timestamp' => \App\Clint\Commands\ConfigTimestampCommand::class,
    'env:get' => \App\Clint\Commands\GetEnvironmentCommand::class,
    'env:switch' => \App\Clint\Commands\SwitchEnvironmentCommand::class,
    'lookup:cache' => \App\Clint\Commands\LookupCacheCommand::class,
    'sitemap:make' => \App\Clint\Commands\SitemapMakeCommand::class,
    'alain:thinks' => \App\Clint\Commands\AlainThinksCommand::class,
    'cards:legality' => \App\Clint\Commands\CardsLegalityCommand::class,

];
