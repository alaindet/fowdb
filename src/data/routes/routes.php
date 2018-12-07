<?php

/*
 | ----------------------------------------------------------------------
 |
 | Define all routes here
 | 
 | Every route is an array like this
 | [ [ httpMethod, uri, controller, method, params, middleware ], ... ]
 | 
 | Ex.:
 | - Register a POST test/update/{id} route
 | - Handle it with TestController::update
 | - Bind {id} uri parameter to its regex pattern [0-9]+ (only digits)
 | - Run the 'captcha' middleware, but *NOT* the 'token' middleware
 | [
 |     'POST',
 |     'test/update/{id}',
 |     'TestController',
 |     'update',
 |     ['d' => '[0-9]+'],
 |     ['!token','captcha']
 | ]
 |
 | -----------------------------------------------------------------------
 */

/**
 * Pre-defined regex patterns to shorten route arrays
 * d => digits only
 * h => digits and letters only (uppercase and lowercase)
 * c => card code specific ex.: NDR-001 U
 */
$d = ['d' => '[0-9]+']; // I => id
$h = ['h' => '[A-Za-z0-9]+']; // H => hash
$c = ['c' => '[A-Z0-9]+\-\d{3}\s[A-Z]+']; // C => card

/**
 * Public routes --------------------------------------------------------------
 * 
 * Anyone can access
 */
$public = [

    ['GET','','HomeController','show'],
    [
        'GET','cr/{version}',
        'GameRulesController','show',
        ['version' => '[0-9]+.[0-9]+[a-z]*']
    ],
    ['GET','cr','GameRulesController','index'],
    ['GET', 'cards/search/help','CardsController','showSearchHelp'],
    ['GET', 'login','Auth\\LoginController','loginForm'],
    ['POST','login','Auth\\LoginController','login', null, ['token']],
    ['GET', 'logout','Auth\\LoginController','logout'],

];

/**
 * User routes ----------------------------------------------------------------
 * 
 * Any logged user can access
 */
$user = [

    ['GET', 'profile','UserController','showProfile'],

];

/**
 * Admin routes ---------------------------------------------------------------
 * 
 * Only admins can access
 */
$admin = [

    // Menu
    ['GET', 'admin','UserController','adminShowProfile'],

    // PHP info
    ['GET', 'phpinfo', 'Admin\\PhpInfoController','showPhpInfo'],

];

/**
 * Judge routes ---------------------------------------------------------------
 * 
 * Only judges can access
 * Bypass: admins
 */
$judge = [

    // Menu
    ['GET', 'judge','UserController','judgeShowProfile'],

    // Cards
    ['GET', 'cards/manage','Admin\\CardsController','indexManage'],
    ['GET', 'cards/create','Admin\\CardsController','createForm'],
    ['POST','cards/create','Admin\\CardsController','create',null,['token']],
    ['GET', 'cards/update/{d}','Admin\\CardsController','updateForm',$d],
    ['POST','cards/update/{d}','Admin\\CardsController','update',$d,['token']],
    ['GET', 'cards/delete/{d}','Admin\\CardsController','deleteForm',$d],
    ['POST','cards/delete/{d}','Admin\\CardsController','delete',$d,['token']],

    // Sets
    ['GET', 'sets/manage','Admin\\SetsController','index'],
    ['GET', 'sets/create','Admin\\SetsController','createForm'],
    ['POST','sets/create','Admin\\SetsController','create',null,['token']],
    ['GET', 'sets/update/{d}','Admin\\SetsController','updateForm',$d],
    ['POST','sets/update/{d}','Admin\\SetsController','update',$d,['token']],
    ['GET', 'sets/delete/{d}','Admin\\SetsController','deleteForm',$d],
    ['POST','sets/delete/{d}','Admin\\SetsController','delete',$d,['token']],

    // Clusters
    ['GET','clusters/manage','Admin\\ClustersController','index'],

    // Rulings
    [
        'GET','rulings/manage',
        'Admin\\RulingsController','index'
    ],
    [
        'GET','rulings/create',
        'Admin\\RulingsController','createForm'
    ],
    [
        'POST','rulings/create',
        'Admin\\RulingsController','create',
        null, ['token']
    ],
    [
        'GET','rulings/update/{d}',
        'Admin\\RulingsController','updateForm',
        $d
    ],
    [
        'POST','rulings/update/{d}',
        'Admin\\RulingsController',
        'update',
        $d, ['token']
    ],
    [
        'GET','rulings/delete/{d}',
        'Admin\\RulingsController','deleteForm',
        $d
    ],
    [
        'POST','rulings/delete/{d}',
        'Admin\\RulingsController','delete',
        $d, ['token']
    ],

    // Banned and limited cards
    [
        'GET','restrictions/manage',
        'Admin\\PlayRestrictionsController','index'
    ],
    [
        'GET','restrictions/create',
        'Admin\\PlayRestrictionsController','createForm'
    ],
    [
        'POST','restrictions/create',
        'Admin\\PlayRestrictionsController','create',
        null, ['token']
    ],
    [
        'GET','restrictions/update/{d}',
        'Admin\\PlayRestrictionsController','updateForm',
        $d
    ],
    [
        'POST','restrictions/update/{d}',
        'Admin\\PlayRestrictionsController','update',
        $d, ['token']
    ],
    [
        'GET','restrictions/delete/{d}',
        'Admin\\PlayRestrictionsController','deleteForm',
        $d
    ],
    [
        'POST','restrictions/delete/{d}',
        'Admin\\PlayRestrictionsController','delete',
        $d,['token']
    ],

    // Comprehensive Rules
    [
        'GET','cr/manage',
        'Admin\\GameRulesController','index'
    ],
    [
        'GET','cr/create',
        'Admin\\GameRulesController','createForm'
    ],
    [
        'POST','cr/create',
        'Admin\\GameRulesController','create',
        null, ['token']
    ],
    [
        'GET','cr/update/{d}',
        'Admin\\GameRulesController','updateForm',
        $d
    ],
    [
        'POST','cr/update/{d}',
        'Admin\\GameRulesController','update',
        $d, ['token']
    ],
    [
        'GET','cr/delete/{d}',
        'Admin\\GameRulesController','deleteForm',
        $d
    ],
    [
        'POST','cr/delete/{d}',
        'Admin\\GameRulesController','delete',
        $d, ['token']
    ],
    [
        'GET','cr/file/{d}',
        'Admin\\GameRulesController','showFile',
        $d
    ],

    // API --------------------------------------------------------------------

    // Clusters
    [
        'GET','api/clusters',
        'Admin\\ClustersController','apiShowAll',
        null, ['!auth', 'api-auth']
    ],
    [
        'GET','api/clusters/{d}',
        'Admin\\ClustersController','apiShow',
        $d, ['!auth', 'api-auth']
    ],
    [
        'POST','api/clusters/create',
        'Admin\\ClustersController','apiCreate',
        null, ['!auth', 'api-auth', 'api-token']
    ],
    [
        'POST','api/clusters/update/{d}',
        'Admin\\ClustersController','apiUpdate',
        $d, ['!auth', 'api-auth', 'api-token']
    ],
    [
        'POST','api/clusters/delete/{d}',
        'Admin\\ClustersController','apiDelete',
        $d, ['!auth', 'api-auth', 'api-token']
    ],

];

/**
 * Return the routes map, grouped by the required role to access them
 */
return [
    'public' => $public,
    'user' => $user,
    'admin' => $admin,
    'judge' => $judge
];
