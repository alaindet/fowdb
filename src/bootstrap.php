<?php

use \App\Services\OpenGraphProtocol\OpenGraphProtocol;
use \App\Services\OpenGraphProtocol\OpenGraphProtocolImage;
use \App\Services\CsrfToken;
use \App\Services\Session;
use \App\Legacy\Authorization;
use \App\Services\Config;
use \App\Exceptions\Handler as ExceptionsHandler;

// Load required files
require __DIR__ . '/vendor/autoload.php';

// Initialize the configuration and the authentication services
$config = Config::getInstance();
Authorization::getInstance();

require __DIR__ . '/app/functions/helpers.php';

// Global exception handling
set_exception_handler([ExceptionsHandler::class, 'handler']);

// Global error handling
// https://stackoverflow.com/a/51091503/5653974
set_error_handler([ExceptionsHandler::class, 'errorHandler'], E_ALL);

// Start the session
Session::start();

// Anti-CSRF token
if (!CsrfToken::exists()) CsrfToken::create();

// Open Graph Protocol tags (See http://ogp.me)
$openGraphProtocol = OpenGraphProtocol::getInstance();
$openGraphProtocol
    ->title($config->get('app.name')) // Can be changed
    ->type($config->get('ogp.type'))
    ->url($config->get('app.url')) // Can be changed
    ->image( // Can be changed
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
