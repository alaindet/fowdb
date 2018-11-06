<?php

// Regex patterns
$d = '[0-9]+'; // I => id
$h = '[A-Za-z0-9]+'; // H => hash
$c = '[A-Z0-9]+\-\d{3}[A-Z]+'; // C => card

// [ [ httpMethod, uri, controller, method, params, middleware ], ... ]
// [ // Example
//     'GET','test/public/{id}',
//     'TestController','testMethod',
//     ['id' => '[0-9]+'],
//     ['!token', '!auth', 'captcha']
// ]
$public = [

    ['GET', 'cards/search/help','CardsController','showSearchHelp'],
    ['GET', 'login','Auth\\LoginController','loginForm'],
    ['POST','login','Auth\\LoginController','login'],
    ['GET', 'logout','Auth\\LoginController','logout'],


    // ['POST','login','LoginController','login'],
    // ['GET', 'logout','LoginController','logout']

];

$user = [

    //

];

$admin = [

    // Menu
    ['GET', 'admin','UserController','adminShowProfile'],

    // Cards
    // ['GET', 'cards/manage','CardsController','indexManage'],
    // ['GET', 'cards/create','CardsController','createForm'],
    // ['POST','cards/create','CardsController','create'],
    // ['GET', 'cards/update/{d}','CardsController','updateForm',['d'=>$d]],
    // ['GET', 'cards/update/{d}','CardsController','update',['d'=>$d]],
    // ['GET', 'cards/delete/{d}','CardsController','deleteForm',['d'=>$d]],
    // ['GET', 'cards/delete/{d}','CardsController','delete',['d'=>$d]],

    // Rulings
    ['GET', 'rulings/manage','RulingsController','indexManage'],
    ['GET', 'rulings/create','RulingsController','createForm'],
    ['POST','rulings/create','RulingsController','create'],
    ['GET', 'rulings/update/{d}','RulingsController','updateForm',['d'=>$d]],
    ['POST','rulings/update/{d}','RulingsController','update',['d'=>$d]],
    ['GET', 'rulings/delete/{d}','RulingsController','deleteForm',['d'=>$d]],
    ['POST','rulings/delete/{d}','RulingsController','delete',['d'=>$d]],

];

// Auth: judge ----------------------------------------------------------------
$judge = [
    
    ['GET', 'judge','UserController','judgeShowProfile'],

];

// [ access => routes, ... ]
return [
    'public' => $public,
    'user' => $user,
    'admin' => $admin,
    'judge' => $judge
];
