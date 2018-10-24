<?php

use \App\Services\OpenGraphProtocol\OpenGraphProtocol;
use \App\Services\OpenGraphProtocol\OpenGraphProtocolImage;
use \App\Services\CsrfToken;
use \App\Services\Session;

// Load required files
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/functions/helpers.php';

// Global exception handling
set_exception_handler([\App\Exceptions\Handler::class, 'handler']);

// Start the session
Session::start();

// Anti-CSRF token
if (!CsrfToken::exists()) CsrfToken::create();

// Open Graph Protocol tags (See http://ogp.me)
$openGraphProtocol = OpenGraphProtocol::getInstance();
$openGraphProtocol
    ->title( config('app.name') ) // Can be changed
    ->type( config('ogp.type') )
    ->url( config('app.url') ) // Can be changed
    ->image( // Can be changed
        (new OpenGraphProtocolImage)
            ->url( config('ogp.image') )
            ->mimeType( config('ogp.image.type') )
            ->width( config('ogp.image.width') )
            ->height( config('ogp.image.height') )
            ->alt( config('app.name') )
    )
    ->siteName( config('app.name') )
    ->locale( config('app.locale') )
    ->description( config('ogp.description') );

// Legacy
$thereWereResults = false;
