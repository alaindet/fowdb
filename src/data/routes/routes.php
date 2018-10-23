<?php

// Regex patterns
$id = '[0-9]+'; // I => id
$hash = '[A-Za-z0-9]+'; // H => hash
$uriFriendly = '[A-Za-z0-9\-]+';
$card = '[A-Z0-9]+\-\d{3}[A-Z]+'; // C => card

// [ [ httpMethod, uri, controller, method, params, middleware ], ... ]
// [ // Example
//     'GET','test/public/{id}',
//     'TestController','testMethod',
//     ['id' => '[0-9]+'],
//     ['!token', '!auth', 'captcha']
// ]
$public = [

    // ['GET', 'login','LoginController','loginForm'],
    // ['POST','login','LoginController','login'],
    // ['GET', 'logout','LoginController','logout']

];

$admin = [

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
