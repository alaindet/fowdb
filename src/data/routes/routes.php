<?php

// [ [ httpMethod, uri, controller, method, params, middleware ], ... ]
// [ // Example
//     'GET','test/public/{id}',
//     'TestController','testMethod',
//     ['id' => '[0-9]+'],
//     ['!token', '!auth', 'captcha']
// ]

// Regex patterns
$d = ['d' => '[0-9]+']; // I => id
$h = ['h' => '[A-Za-z0-9]+']; // H => hash
$c = ['c' => '[A-Z0-9]+\-\d{3}[A-Z]+']; // C => card

// Public routes --------------------------------------------------------------
$public = [

    ['GET', 'cards/search/help','CardsController','showSearchHelp'],
    ['GET', 'login','Auth\\LoginController','loginForm'],
    ['POST','login','Auth\\LoginController','login'],
    ['GET', 'logout','Auth\\LoginController','logout'],

];

// User (logged) routes -------------------------------------------------------
$user = [

    ['GET', 'profile','UserController','showProfile'],

];

// Admin routes ---------------------------------------------------------------
$admin = [

    // Menu
    ['GET', 'admin','UserController','adminShowProfile'],

    // PHP info
    ['GET', 'phpinfo', 'Admin\\PhpInfoController','showPhpInfo'],

];

// Judge routes (Admins too as they bypass any authorization) -----------------
$judge = [

    // Menu
    ['GET', 'judge','UserController','judgeShowProfile'],

    // Cards
    ['GET', 'cards/manage','Admin\\CardsController','indexManage'],
    ['GET', 'cards/create','Admin\\CardsController','createForm'],
    ['POST','cards/create','Admin\\CardsController','create'],
    ['GET', 'cards/update/{d}','Admin\\CardsController','updateForm',$d],
    ['GET', 'cards/update/{d}','Admin\\CardsController','update',$d],
    ['GET', 'cards/delete/{d}','Admin\\CardsController','deleteForm',$d],
    ['GET', 'cards/delete/{d}','Admin\\CardsController','delete',$d],

    // Rulings
    ['GET', 'rulings/manage','RulingsController','indexManage'],
    ['GET', 'rulings/create','RulingsController','createForm'],
    ['POST','rulings/create','RulingsController','create'],
    ['GET', 'rulings/update/{d}','RulingsController','updateForm',$d],
    ['POST','rulings/update/{d}','RulingsController','update',$d],
    ['GET', 'rulings/delete/{d}','RulingsController','deleteForm',$d],
    ['POST','rulings/delete/{d}','RulingsController','delete',$d],

];

// [ role_required => routes, ... ]
return [
    'public' => $public,
    'user' => $user,
    'admin' => $admin,
    'judge' => $judge
];
