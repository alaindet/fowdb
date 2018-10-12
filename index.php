<?php

// Init the app
require __DIR__ . '/_config/config.php';

// Search for cards (spaghetti code here!)
if (isset($_GET['do']) AND $_GET['do'] == 'search') {
    include DIR_ROOT . '/search/search_retrievedb.php';
    exit();
}

// Show search page as home page if no page parameter passed
if (!isset($_GET['p'])) {
    return view('Search', 'search/search.php', ['js' => ['search']]);
}

// Routing
if (isset($_GET['p'])) {
    switch($_GET['p']) {

        // Temp/admin/artists
        case 'temp/admin/artists/select-set':
            view('FoWDB Craton', 'admin/_artists/select-set.php');
            return;
        case 'temp/admin/artists/select-card':
            view('FoWDB Craton', 'admin/_artists/select-card.php');
            return;
        case 'temp/admin/artists/card':
            view(
                $title = 'FoWDB Craton',
                $path = 'admin/_artists/card.php',
                $options = [
                    'js' => ['_artists'],
                    'jqueryui' => 1    
                ]
            );
            return;
            
        case 'admin':
            view('Admin Menu', 'admin/index.php');
            exit();

        case 'admin/cards':
        case 'manage-cards':
            view('Admin - Cards', 'admin/manage-cards/index.php');
            exit();

        case 'admin/database':
            if (admin_level() === 0) {
                echo "You're not authorized";
                return;
            }
            $hash = 'cJ3MRhFC8zNuuv4Eo6pNGCx7HfbznvOAdEZT9Ylt7AG';
            $url = APP_URL . '/admin/database/' . $hash . '/index.php';
            header("Location: {$url}");
            return;
            
        case 'admin/cr':
            view('Admin - Comprehensive Rules', 'admin/cr/index.php');
            exit();
            
        case 'admin/cr/action':
            require DIR_ROOT . "/admin/cr/actions.php";
            exit();

        case 'admin/cr/raw':
            view('Admin - Helpers', '/admin/cr/view-txt.php');
            exit();

        case 'admin/hash':
        case 'hash':
            view('Admin - Hash', 'admin/hash/index.php');
            exit();

        case 'admin/helpers':
            view(
                $title = 'Admin - Helpers',
                $path = 'admin/helpers/index.php',
                $options = null,
                $vars = null,
                $minize = false
            );
            exit();

        case 'admin/php':
            if (admin_level() == 1) { phpinfo(); }
            exit();

        case 'manage-rulings': // Legacy
        case 'admin/rulings':
            view('Admin - Rulings', 'admin/manage-rulings/index.php',[
                'js' => ['manage-rulings'],
                'jqueryui' => 1,
                'lightbox' => 1
            ]);
            exit();

        case 'admin/trim-image':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    require __DIR__ . '/admin/image-trim/process.php';
                    break;
                case 'GET':
                default:
                    view('Admin - Trim image', 'admin/image-trim/form.php');
                    break;
            }
            return;

        case 'card':
            include 'cardpage/ogpinfo.sql.php'; // Get info for OGP
            view(
                $page_title,
                'cardpage/cardpage.php',
                ['js'=>['cardpage']]
            );
            exit();

        case 'resources/ban':
            view('Banlist', 'resources/ban/index.php');
            exit();

        case 'resources/cr':
            require DIR_ROOT . "/resources/cr/index.php";
            exit();
            
        case 'resources/errata':
        case 'errata': // Legacy
            view(
                'Errata'
                ,'resources/errata/errata.php'
            );
            exit();

        case 'resources/formats':
            view('Formats', 'resources/formats/index.php');
            exit();

        case 'races-traits': // Legacy
        case 'resources/races':
            view('Races and traits', 'resources/races-traits/races-traits.php');
            exit();

        case 'rulers': // Legacy
        case 'resources/rulers':
            view('Rulers', 'resources/rulers/rulers.php');
            exit();

        case 'rulings':
            view('Rulings', 'resources/rulings/index.php');
            exit();

        case 'search':
            view('Search', 'search/search.php', [
                'lightbox' => 1,
                'js' => ['search']
            ]);
            exit();
        
        case 'spoiler':
            view('Spoiler', 'spoiler/spoiler.php', [
                'lightbox' => 1,
                'js' => ['search', 'spoiler']
            ]);
            exit();
    }
}
