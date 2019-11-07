<?php

require __DIR__ . '/all.php'; // Declares $config

$config->set('app.mode', 'web');

\App\Services\Session::start();
\App\Services\CsrfToken::createOrRefresh();
\App\Legacy\Authorization::getInstance();

$configs = $config->getByKeys([
    'app.locale',
    'app.name',
    'app.url',
    'ogp.description',
    'ogp.image.height',
    'ogp.image.type',
    'ogp.image.width',
    'ogp.image',
    'ogp.type',
]);

// Open Graph Protocol tags (See http://ogp.me)
(\App\Services\OpenGraphProtocol\OpenGraphProtocol::getInstance())
    ->title($configs['app.name'])
    ->type($configs['ogp.type'])
    ->url($configs['app.url'])
    ->image(
        (new \App\Services\OpenGraphProtocol\OpenGraphProtocolImage)
            ->url($configs['ogp.image'])
            ->mimeType($configs['ogp.image.type'])
            ->width($configs['ogp.image.width'])
            ->height($configs['ogp.image.height'])
            ->alt($configs['app.name'])
    )
    ->siteName($configs['app.name'])
    ->locale($configs['app.locale'])
    ->description($configs['ogp.description']);
