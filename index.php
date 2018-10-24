<?php

require __DIR__ . '/src/bootstrap.php';

// Search for cards (spaghetti code here!)
if (isset($_GET['do']) && $_GET['do'] === 'search') {
    require path_root('search/search_retrievedb.php');
    return;
}

// Old routing
if (isset($_GET['p'])) {
    switch($_GET['p']) {

        // Public -------------------------------------------------------------
        case 'search':
            $options = [ 'lightbox' => 1, 'js' => ['search'] ];
            echo view('Search', 'search/search.php', $options);
            return;
        case 'card':
            require __DIR__ . '/cardpage/cardpage.php';
            return;
        case 'spoiler':
            $options = [ 'lightbox' => 1, 'js' => ['search', 'spoiler'] ];
            echo view('Spoiler', 'spoiler/spoiler.php', $options);
            return;
        case 'resources/ban':
            echo view('Banlist', 'resources/ban/index.php');
            return;
        case 'resources/cr':
            require path_root('resources/cr/index.php');
            return;
        case 'resources/errata':
            echo view('Errata', 'resources/errata/errata.php');
            return;
        case 'resources/formats':
            echo view('Formats', 'resources/formats/index.php');
            return;
        case 'resources/races':
            echo view('Races and traits', 'resources/races/races.php');
            return;

        // Admin --------------------------------------------------------------
        case 'admin':
            echo view('Admin:Menu', 'admin/index.php');
            return;
        case 'admin/cards':
            echo view('Admin:Cards', 'admin/cards/index.php');
            return;
        case 'admin/database':
            if (admin_level() > 0) {
                $hash = 'cJ3MRhFC8zNuuv4Eo6pNGCx7HfbznvOAdEZT9Ylt7AG';
                $url = config('app.url');
                $url .= "/admin/database/{$hash}/index.php";
                header("Location: {$url}");
            }
            return;
        case 'admin/cr':
            echo view('Admin - Comprehensive Rules', 'admin/cr/index.php');
            return;
        case 'admin/cr/action':
            require path_root('admin/cr/actions.php');
            return;
        case 'admin/cr/raw':
            echo view('Admin:Helpers', 'admin/cr/view-txt.php');
            return;
        case 'admin/hash':
            echo view('Admin:Hash', 'admin/hash/index.php');
            return;
        case 'admin/helpers':
            echo view(
                $title = 'Admin:Helpers',
                $path = 'admin/helpers/index.php',
                $options = null,
                $vars = null,
                $minize = false
            );
            return;
        case 'admin/php':
            if (admin_level() == 1) phpinfo();
            return;
        case 'admin/rulings':
            $options = [
                'js' => ['manage-rulings'],
                'jqueryui' => true,
                'lightbox' => true
            ];
            echo view('Admin - Rulings', 'admin/rulings/index.php', $options);
            return;
        case 'admin/trim-image':
            $method = $_SERVER['REQUEST_METHOD'];
            if ($method === 'GET') {
                echo view('Admin:Trim image', 'admin/image-trim/form.php');
            }
            elseif ($method === 'POST') {
                require path_root('admin/image-trim/process.php');
            }
            return;
        case 'admin/clint':
            echo view('Admin:Clint', 'admin/clint/index.php');
            return;
        case 'admin/lookup':
            echo view(
                'Admin:Lookup',
                'admin/lookup/index.php',
                null, null, $minimize = false
            );
            return;

        // Temporary ----------------------------------------------------------
        case 'temp/admin/artists/select-set':
            echo view('FoWDB Craton', 'admin/_artists/select-set.php');
            return;
        case 'temp/admin/artists/select-card':
            echo view('FoWDB Craton', 'admin/_artists/select-card.php');
            return;
        case 'temp/admin/artists/card':
            $options = [ 'js' => ['_artists'], 'jqueryui' => true ];
            echo view('FoWDB Craton', 'admin/_artists/card.php', $options);
            return;
    }
}


// Real router (will gradually replace all code above) ------------------------

// TEMPORARY - Go to homepage
if ($_SERVER['REQUEST_URI'] === '/') {
    echo view('Search', 'search/search.php', [ 'js' => ['search'] ]);
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

// Map request to its route
$route = (new \App\Http\Response\Router())
    ->setRoutes(load_file(path_src('data/routes/routes.php')))
    ->setRequest($request)
    ->match();

// Set needed access level
$request->app('access', $route['_access']);

// Initialize the dispatcher and pass the routed data
// Ex.:
// Request URI => /a-cool-uri/abc123/here-is-the-id/456
// Route => /a-cool-uri/{code}/here-is-the-id/{id}
// Route data => [
//     '_controller' => 'CardsController',
//     '_method' => 'searchForm',
//     '_access' => 'public',
//     '_route' => 'GET/',
//     'code' => 'abc123',
//     'id' => 456
// ]
$response = (new \App\Http\Response\Dispatcher())
    ->setRequest($request)
    ->setMatchedRoute($route)
    ->runMiddleware()
    ->dispatch();

echo $response;
