<?php

// Init the app
require __DIR__ . '/_config/config.php';

// Search for cards (spaghetti code here!)
if (isset($_GET['do']) AND $_GET['do'] == 'search') {
    include APP_ROOT . '/search/search_retrievedb.php';
    exit();
}

// Show search page as home page if no page parameter passed
if (! isset($_GET['p'])) {
    \App\Page::build('Search', 'search/search.php', ['js' => ['search']]);
    exit();
}

// Routing
if (isset($_GET['p'])) {
        
    switch($_GET['p']) {
            
        case 'admin':
            \App\Page::build('Admin Menu', 'admin/index.php');
            exit();

        case 'admin/cards':
        case 'manage-cards':
            \App\Page::build('Admin - Cards', 'admin/manage-cards/index.php');
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
            \App\Page::build('Admin - Comprehensive Rules', 'admin/cr/index.php');
            exit();
            
        case 'admin/cr/action':
            require APP_ROOT . "/admin/cr/actions.php";
            exit();

        case 'admin/cr/raw':
            \App\Page::build('Admin - Helpers', '/admin/cr/view-txt.php');
            exit();

        case 'admin/hash':
        case 'hash':
            \App\Page::build('Admin - Hash', 'admin/hash/index.php');
            exit();

        case 'admin/helpers':
            \App\Page::build(
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
            \App\Page::build('Admin - Rulings', 'admin/manage-rulings/index.php',[
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
                    \App\Page::build('Admin - Trim image', 'admin/image-trim/form.php');
                    break;
            }
            return;

        case 'card':
            include 'cardpage/ogpinfo.sql.php'; // Get info for OGP
            \App\Page::build(
                $page_title,
                'cardpage/cardpage.php',
                ['js'=>['cardpage']]
            );
            exit();

        case 'resources/ban':
            \App\Page::build('Banlist', 'resources/ban/index.php');
            exit();

        case 'resources/cr':
            require APP_ROOT . "/resources/cr/index.php";
            exit();
            
        case 'resources/errata':
        case 'errata': // Legacy
            \App\Page::build(
                'Errata'
                ,'resources/errata/errata.php'
            );
            exit();

        case 'resources/formats':
            \App\Page::build('Formats', 'resources/formats/index.php');
            exit();

        case 'races-traits': // Legacy
        case 'resources/races':
            \App\Page::build('Races and traits', 'resources/races-traits/races-traits.php');
            exit();

        case 'rulers': // Legacy
        case 'resources/rulers':
            \App\Page::build('Rulers', 'resources/rulers/rulers.php');
            exit();

        case 'rulings':
            \App\Page::build('Rulings', 'resources/rulings/index.php');
            exit();

        case 'search':
            \App\Page::build('Search', 'search/search.php', [
                'lightbox' => 1,
                'js' => ['search']
            ]);
            exit();
        
        case 'spoiler':
            \App\Page::build('Spoiler', 'spoiler/spoiler.php', [
                'lightbox' => 1,
                'js' => ['search', 'spoiler']
            ]);
            exit();
    }
}
