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
// $h = ['h' => '[A-Za-z0-9]+']; // H => hash

/**
 * Public routes --------------------------------------------------------------
 * 
 * Anyone can access
 */
$public = [

    ['GET', '','CardsController','searchForm'],
    ['GET', 'search','CardsController','searchForm'], // Alias
    ['GET', 'cards/search','CardsController','searchForm'], // Alias
    ['GET', 'cards','CardsController','search'],
    ['GET', 'spoiler','SpoilersController','index'],
    ['GET', 'card/{code}','CardsController','show',['code' => '[A-Z0-9]+\-\d{3}[\s+]*[A-Z]*']],
    ['GET', 'cr/{version}','GameRulesController','show',['version' => '[0-9]+.[0-9]+[a-z]*']],
    ['GET', 'cr','GameRulesController','index'],
    ['GET', 'races','RacesController','index'],
    ['GET', 'formats','FormatsController','index'],
    ['GET', 'errata','ErrataController','index'],
    ['GET', 'banlist','PlayRestrictionsController','index'],
    ['GET', 'restrictions','PlayRestrictionsController','index'], // Alias
    ['GET', 'cards/search/help','CardsController','searchHelp'],
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

    // Comprehensive Rules
    ['GET', 'cr/manage','Admin\\GameRulesController','index'],
    ['GET', 'cr/create','Admin\\GameRulesController','createForm'],
    ['POST','cr/create', 'Admin\\GameRulesController','create',null, ['token']],
    ['GET', 'cr/update/{d}', 'Admin\\GameRulesController','updateForm',$d],
    ['POST','cr/update/{d}', 'Admin\\GameRulesController','update',$d, ['token']],
    ['GET', 'cr/delete/{d}', 'Admin\\GameRulesController','deleteForm',$d],
    ['POST','cr/delete/{d}', 'Admin\\GameRulesController','delete',$d, ['token']],
    ['GET', 'cr/file/{d}', 'Admin\\GameRulesController','showFile',$d],

    // Lookup
    ['GET', 'lookup','Admin\\LookupController','index'],
    ['GET', 'lookup/build','Admin\\LookupController','buildAll'],
    ['GET', 'lookup/read','Admin\\LookupController','read'],
    ['GET', 'lookup/read/{feature}', 'Admin\\LookupController','read',['feature' => '[a-z]+']],

    // Trim an image
    ['GET', 'images/trim','Admin\\ImagesController','trimForm'],
    ['POST','images/trim','Admin\\ImagesController','trim',null, ['token']],

    // Hash a string
    ['GET', 'hash', 'Admin\\HashController','hashForm'],
    ['POST','hash', 'Admin\\HashController','hash',null, ['token']],

    // Clint CLI commands
    ['GET', 'clint', 'Admin\\ClintController','showForm'],
    ['GET', 'clint/{command}', 'Admin\\ClintController','executeCommand',['command' => '[A-Za-z-]+']],

    // Artists tool
    ['GET', 'artists', 'Admin\\ArtistsController','selectSetForm'],
    ['GET', 'artists/set/{set}', 'Admin\\ArtistsController','selectCardForm', ['set' => '[0-9]+']],
    ['GET', 'artists/card/{card}', 'Admin\\ArtistsController','cardForm', ['set' => '[0-9]+', 'card' => '[0-9]+']],
    ['POST','artists/store', 'Admin\\ArtistsController','store',null, ['token']],
    ['GET', 'api/artists/autocomplete', 'Api\\ArtistsController','autocomplete',null, ['!auth', 'api-auth']],

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
    ['GET', 'sets/manage','Admin\\GameSetsController','index'],
    ['GET', 'sets/create','Admin\\GameSetsController','createForm'],
    ['POST','sets/create','Admin\\GameSetsController','create',null,['token']],
    ['GET', 'sets/update/{d}','Admin\\GameSetsController','updateForm',$d],
    ['POST','sets/update/{d}','Admin\\GameSetsController','update',$d,['token']],
    ['GET', 'sets/delete/{d}','Admin\\GameSetsController','deleteForm',$d],
    ['POST','sets/delete/{d}','Admin\\GameSetsController','delete',$d,['token']],

    // Formats
    ['GET', 'formats/manage', 'Admin\\GameFormatsController', 'index'],
    ['GET', 'formats/create', 'Admin\\GameFormatsController', 'createForm'],
    ['POST','formats/create', 'Admin\\GameFormatsController', 'create', null, ['token']],
    ['GET', 'formats/update/{d}','Admin\\GameFormatsController','updateForm',$d],
    ['POST','formats/update/{d}','Admin\\GameFormatsController','update',$d,['token']],
    ['GET', 'formats/delete/{d}','Admin\\GameFormatsController','deleteForm',$d],
    ['POST','formats/delete/{d}','Admin\\GameFormatsController','delete',$d,['token']],

    // Clusters
    ['GET','clusters/manage','Admin\\GameClustersController','index'],

    // Rulings
    ['GET', 'rulings/manage','Admin\\GameRulingsController','index'],
    ['GET', 'rulings/create','Admin\\GameRulingsController','createForm'],
    ['POST','rulings/create','Admin\\GameRulingsController','create',null, ['token']],
    ['GET', 'rulings/update/{d}','Admin\\GameRulingsController','updateForm',$d],
    ['POST','rulings/update/{d}','Admin\\GameRulingsController','update',$d, ['token']],
    ['GET', 'rulings/delete/{d}','Admin\\GameRulingsController','deleteForm',$d],
    ['POST','rulings/delete/{d}','Admin\\GameRulingsController','delete',$d, ['token']],

    // Banned and limited cards
    ['GET', 'restrictions/manage','Admin\\PlayRestrictionsController','index'],
    ['GET', 'restrictions/create','Admin\\PlayRestrictionsController','createForm'],
    ['POST','restrictions/create','Admin\\PlayRestrictionsController','create',null, ['token']],
    ['GET', 'restrictions/update/{d}','Admin\\PlayRestrictionsController','updateForm',$d],
    ['POST','restrictions/update/{d}','Admin\\PlayRestrictionsController','update',$d, ['token']],
    ['GET', 'restrictions/delete/{d}','Admin\\PlayRestrictionsController','deleteForm',$d],
    ['POST','restrictions/delete/{d}','Admin\\PlayRestrictionsController','delete',$d,['token']],

    // API --------------------------------------------------------------------

    // Clusters
    ['GET', 'api/clusters',
        'Admin\\GameClustersController','apiShowAll', null, ['!auth', 'api-auth']],
    ['GET', 'api/clusters/{d}',
        'Admin\\GameClustersController','apiShow', $d, ['!auth', 'api-auth']],
    ['POST','api/clusters/create',
        'Admin\\GameClustersController','apiCreate', null, ['!auth', 'api-auth', 'api-token']],
    ['POST','api/clusters/update/{d}',
        'Admin\\GameClustersController','apiUpdate', $d, ['!auth', 'api-auth', 'api-token']],
    ['POST','api/clusters/delete/{d}',
        'Admin\\GameClustersController','apiDelete', $d, ['!auth', 'api-auth', 'api-token']],

    // Cards
    ['GET', 'api/cards/autocomplete/names',
        'Api\\CardsController','autocompleteNames',null,['!auth', 'api-auth']],
    ['GET', 'api/cards/check/{d}',
        'Api\\CardsController','checkId',$d,['!auth', 'api-auth']],

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
