<?php

require __DIR__ . '/src/bootstrap.php';

// Search for cards (spaghetti code here!)
if (isset($_GET['do']) && $_GET['do'] === 'search') {
    require path_root('old/search/search_retrievedb.php');
    return;
}

// Old routing
if (isset($_GET['p'])) {
    switch($_GET['p']) {

        // Public -------------------------------------------------------------
        case 'search':
            echo view_old(
                'Search',
                'old/search/search.php',
                [
                    'lightbox' => true,
                    'js' => [ 'public/search' ]
                ],
                ['thereWereResults' => false]
            );
            return;
        case 'card':
            require __DIR__ . '/old/cardpage/cardpage.php';
            return;
        case 'spoiler':;
            echo view_old('Spoiler', 'old/spoiler/spoiler.php', [
                'lightbox' => true,
                'js' => [
                    'public/search',
                    'public/spoiler'
                ]
            ]);
            return;
        case 'resources/ban':
            echo view_old('Banlist', 'old/resources/ban/index.php');
            return;
        case 'resources/cr':
            require path_root('old/resources/cr/index.php');
            return;
        case 'resources/errata':
            echo view_old('Errata', 'old/resources/errata/errata.php');
            return;
        case 'resources/formats':
            echo view_old('Formats', 'old/resources/formats/index.php');
            return;
        case 'resources/races':
            echo view_old('Races and traits', 'old/resources/races/races.php');
            return;

        // Admin --------------------------------------------------------------
        // case 'admin':
        //     echo view_old('Admin:Menu', 'old/admin/index.php');
        //     return;
        // case 'admin/cards':
        //     echo view_old('Admin:Cards', 'old/admin/cards/index.php');
        //     return;
        case 'admin/database':
            \App\Legacy\Authorization::allow([1]);
            \App\Http\Response\Redirect::to(implode('', [
                '/old/admin/database/',
                'cJ3MRhFC8zNuuv4Eo6pNGCx7HfbznvOAdEZT9Ylt7AG',
                '/index.php'
            ]));
        case 'admin/cr':
            echo view_old(
                'Admin - Comprehensive Rules',
                'old/admin/cr/index.php'
            );
            return;
        case 'admin/cr/action':
            require path_root('old/admin/cr/actions.php');
            return;
        case 'admin/cr/raw':
            echo view_old('Admin:Helpers', 'old/admin/cr/view-txt.php');
            return;
        case 'admin/hash':
            echo view_old('Admin:Hash', 'old/admin/hash/index.php');
            return;
        case 'admin/helpers':
            echo view_old(
                $title = 'Admin:Helpers',
                $path = 'old/admin/helpers/index.php',
                $options = null,
                $vars = null,
                $minize = false
            );
            return;
        case 'admin/php':
            \App\Legacy\Authorization::allow([1]);
            phpinfo();
            return;
        // case 'admin/rulings':
        //     $options = [
        //         'js' => ['manage-rulings'],
        //         'jqueryui' => true,
        //         'lightbox' => true
        //     ];
        //     echo view_old('Admin - Rulings', 'admin/rulings/index.php', $options);
        //     return;
        case 'admin/trim-image':
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method === 'GET') {
                echo view_old(
                    'Admin:Trim image',
                    'old/admin/image-trim/form.php'
                );
            } elseif ($method === 'POST') {
                require path_root('old/admin/image-trim/process.php');
            }
            return;
        case 'admin/clint':
            echo view_old('Admin:Clint', 'old/admin/clint/index.php');
            return;
        case 'admin/lookup':
            echo view_old(
                'Admin:Lookup',
                'old/admin/lookup/index.php',
                null, null, $minimize = false
            );
            return;

        // Temporary ----------------------------------------------------------
        case 'admin/_artists/select-set':
            echo view_old('FoWDB Craton', 'old/admin/_artists/select-set.php');
            return;
        case 'admin/_artists/select-card':
            echo view_old('FoWDB Craton', 'old/admin/_artists/select-card.php');
            return;
        case 'admin/_artists/card':
            echo view_old(
                'FoWDB Craton',
                'old/admin/_artists/card.php',
                [
                    'jqueryui' => true,
                    'lightbox' => true,
                    'js' => [ 'admin/_artists' ]
                ]
            );
            return;
    }
}


// Real router (will gradually replace all code above) ------------------------

// TEMPORARY - Go to homepage
if ($_SERVER['REQUEST_URI'] === '/') {
    echo view_old(
        'Search',
        'old/search/search.php',
        [ 'js' => [ 'public/search' ] ],
        ['thereWereResults' => false]
    );
    return;
}

// Generate HTTP request
$request = (new \App\Http\Request\Request)
    ->baseUrl('/')
    ->method($_SERVER['REQUEST_METHOD'] ?? 'GET')
    ->host($_SERVER['HTTP_HOST'] ?? config('app.host'))
    ->scheme($_SERVER['REQUEST_SCHEME'] ?? 'http')
    ->httpPort(80)
    ->httpsPort(443)
    ->path($_SERVER['REQUEST_URI'] ?? '/')
    ->queryString($_SERVER['QUERY_STRING']);

// Read the routes
$routes = \App\Services\FileSystem::loadFile(path_data('routes/routes.php'));

// Map request to its route
$route = (new \App\Http\Response\Router())
    ->setRoutes($routes)
    ->setRequest($request)
    ->match();

// Set needed access level
$request->app('access', $route['_access']);

// Initialize the dispatcher
$response = (new \App\Http\Response\Dispatcher())
    ->setRequest($request)
    ->setMatchedRoute($route)
    ->runMiddleware()
    ->dispatch();

echo $response;
