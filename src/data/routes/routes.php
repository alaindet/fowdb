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
    ['POST','login','Auth\\LoginController','login', null, ['token']],
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
    ['POST','cards/create','Admin\\CardsController','create',null,['token']],
    ['GET', 'cards/update/{d}','Admin\\CardsController','updateForm',$d],
    ['POST','cards/update/{d}','Admin\\CardsController','update',$d,['token']],
    ['GET', 'cards/delete/{d}','Admin\\CardsController','deleteForm',$d],
    ['POST','cards/delete/{d}','Admin\\CardsController','delete',$d,['token']],

    // Clusters
    ['GET', 'clusters/manage', 'ClustersController','indexManage'],
    ['GET', 'clusters/create', 'ClustersController','createForm'],
    ['POST','clusters/create', 'ClustersController','create',null,['token']],
    ['GET', 'clusters/update/{d}','ClustersController','updateForm',$d],
    ['POST','clusters/update/{d}','ClustersController','update',$d,['token']],
    ['GET', 'clusters/delete/{d}','ClustersController','deleteForm',$d],
    ['POST','clusters/delete/{d}','ClustersController','delete',$d,['token']],

    // Rulings
    ['GET', 'rulings/manage','RulingsController','indexManage'],
    ['GET', 'rulings/create','RulingsController','createForm'],
    ['POST','rulings/create','RulingsController','create',null,['token']],
    ['GET', 'rulings/update/{d}','RulingsController','updateForm',$d],
    ['POST','rulings/update/{d}','RulingsController','update',$d,['token']],
    ['GET', 'rulings/delete/{d}','RulingsController','deleteForm',$d],
    ['POST','rulings/delete/{d}','RulingsController','delete',$d,['token']],

    // API --------------------------------------------------------------------

    // Clusters TEST
    ['GET', 'api/clusters','ClustersController','apiShowAll',null,
        ['!auth', 'api-auth']
    ],
    ['GET', 'api/clusters/{d}','ClustersController','apiShow',$d,
        ['!auth', 'api-auth']
    ],
    ['POST','api/clusters/create','ClustersController','apiCreate',null,
        ['!auth', 'api-auth', 'api-token']
    ],
    ['POST','api/clusters/update/{d}','ClustersController','apiUpdate',$d,
        ['!auth', 'api-auth', 'api-token']
    ],
    ['POST','api/clusters/delete/{d}','ClustersController','apiDelete',$d,
        ['!auth', 'api-auth', 'api-token']
    ],

];

// [ role_required => routes, ... ]
return [
    'public' => $public,
    'user' => $user,
    'admin' => $admin,
    'judge' => $judge
];
