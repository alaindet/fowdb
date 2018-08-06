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

        // case 'nojs-search':
        //  require 'search/nojs.php';
        //  exit();
            
        case 'admin':
            \App\Page::build('Admin Menu', 'admin/index.php');
            exit();

        // case 'admin/ban':
        //  \App\Page::build('Admin - Banlists', 'admin/ban/index.php');
        //  exit();

        case 'manage-cards':
        case 'admin/cards':
            \App\Page::build('Admin - Cards', 'admin/manage-cards/index.php');
            exit();
            
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
            \App\Page::build('Admin - Helpers', 'admin/helpers/index.php');
            exit();

        // case 'admin/image':
        //  \App\Page::build('Admin - Upload image', 'admin/upload-image/index.php');
        //  exit();

        case 'admin/php':
            if (admin_level() == 1) { phpinfo(); }
            exit();

        // case 'manage-requests': // Legacy
        // case 'admin/requests':
        //  \App\Page::build('Admin - Manage Requests', 'admin/manage-requests/index.php', [
        //      'js' => ['admin/requests'],
        //      'lightbox' => 1
        //  ]);
        //  exit();

        case 'manage-rulings': // Legacy
        case 'admin/rulings':
            \App\Page::build('Admin - Rulings', 'admin/manage-rulings/index.php',[
                'js' => ['manage-rulings'],
                'jqueryui' => 1,
                'lightbox' => 1
            ]);
            exit();

        case 'admin/trim-image':
            \App\Page::build('Admin - Trim image', 'admin/image-trim/index.php');
            return;

        case 'card':
            include 'cardpage/ogpinfo.sql.php'; // Get info for OGP
            \App\Page::build($page_title, 'cardpage/cardpage.php', ['js'=>['cardpage']]);
            exit();

        // case 'request-ruling': // Legacy
        // case 'card/request':
        //  \App\Page::build('Request ruling', 'request-ruling/index.php' ,[
        //      'lightbox' => 1,
        //      'js' => ['request-ruling']
        //  ]);
        //  exit();

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
