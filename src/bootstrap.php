<?php

use App\Services\OpenGraphProtocol\OpenGraphProtocol;
use App\Services\OpenGraphProtocol\OpenGraphProtocolImage;
use App\Services\CsrfToken;
use App\Services\Session\Session;
use App\Legacy\Authorization;
use App\Services\Configuration\Configuration;
use App\Base\Exceptions\Handler;

// Load required files
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/functions/helpers.php';

// Global exception handling
set_exception_handler([Handler::class, 'handler']);

// Global error handling
// https://stackoverflow.com/a/51091503/5653974
set_error_handler([Handler::class, 'errorHandler'], E_ALL);

// Initialize services
$config = Configuration::getInstance();
$openGraphProtocol = OpenGraphProtocol::getInstance();
Authorization::getInstance();
Session::start();
if (!CsrfToken::exists()) {
    CsrfToken::create();
}

// Open Graph Protocol tags (See http://ogp.me)
// Title, url and image can be changed by specific pages
$openGraphProtocol
    ->title($config->get('app.name'))
    ->type($config->get('ogp.type'))
    ->url($config->get('app.url'))
    ->image(
        (new OpenGraphProtocolImage)
            ->url($config->get('ogp.image'))
            ->mimeType($config->get('ogp.image.type'))
            ->width($config->get('ogp.image.width'))
            ->height($config->get('ogp.image.height'))
            ->alt($config->get('app.name'))
    )
    ->siteName($config->get('app.name'))
    ->locale($config->get('app.locale'))
    ->description($config->get('ogp.description'));
