<?php

// Regex patterns
$id = '[0-9]+'; // I => id
$hash = '[A-Za-z0-9]+'; // H => hash
$uriFriendly = '[A-Za-z0-9\-]+';
$card = '[A-Z0-9]+\-\d{3}[A-Z]+'; // C => card

// [ [ httpMethod, uri, controller, method, params, middleware ], ... ]
$public = [

    ['GET', 'test/public','TestController','publicMenu'],
    ['GET', 'test/public/static','TestController','publicStaticForm'],
    ['POST','test/public/static','TestController','publicStaticProcess'],
    [
        'GET', 'test/public/params/{id}/{hash}',
        'TestController','publicRouteParams',
        ['id' => $id, 'hash' => $uriFriendly]
    ]

];

$admin = [

    ['GET', 'test/admin','TestController','adminMenu'],
    ['GET', 'test/admin/static','TestController','adminStaticForm'],
    ['POST','test/admin/static','TestController','adminStaticProcess'],
    [
        'GET', 'test/admin/params/{id}/{hash}',
        'TestController','adminRouteParams',
        ['id' => $id, 'hash' => $uriFriendly]
    ]

    // Clusters
    // ['GET', 'clusters/create','ClustersController','createFrom'],
    // ['POST','clusters','ClustersController','create'],
    // ['GET', 'clusters/manage','ClustersController','indexManage'],
    // ['GET', 'clusters/update/{d}','ClustersController','updateForm', $d],
    // ['POST','clusters/update/{d}','ClustersController','update', $d],
    // ['GET', 'clusters/delete/{d}','ClustersController','deleteForm', $d],
    // ['POST','clusters/delete/{d}','ClustersController','delete', $d],

];

// Auth: judge ----------------------------------------------------------------
$judge = [
    
    //

];

// [ access => routes, ... ]
return [
    'public' => $public,
    'admin' => $admin,
    'judge' => $judge
];
