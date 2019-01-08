<?php

/*
 | ----------------------------------------------------------------------------
 |
 | Define all TEST routes here
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
 | PLEASE NOTE:
 | - All routes are prefixed with 'test/' (ex.: foo => test/foo)
 | - All controllers are prefixed 'Test\\' (they must reside inside Test dir)
 |
 | ----------------------------------------------------------------------------
 */

/**
 * Public routes --------------------------------------------------------------
 * 
 * Anyone can access
 */
$public = [

    ['GET', '', 'HomeController', 'index'],
    ['GET', 'button-checkboxes', 'ComponentsController', 'buttonCheckboxes'],
    ['GET', 'button-checkbox', 'ComponentsController', 'buttonCheckbox'],
    ['GET', 'button-dropdown', 'ComponentsController', 'buttonDropdown'],
    ['GET', 'input-dropdown', 'ComponentsController', 'inputDropdown'],

];

/**
 * User routes ----------------------------------------------------------------
 * 
 * Any logged user can access
 */
$user = [

    //

];

/**
 * Admin routes ---------------------------------------------------------------
 * 
 * Only admins can access
 */
$admin = [

    //

];

/**
 * Judge routes ---------------------------------------------------------------
 * 
 * Only judges can access
 * Bypass: admins
 */
$judge = [

    //

];

/**
 * Return the routes map, grouped by the required role to access them
 */
$routes = [
    'public' => $public,
    'user' => $user,
    'admin' => $admin,
    'judge' => $judge
];

// Prefix routes and controllers with test/ and Test\\
$urls = [];
foreach ($routes as $role => &$routesGroup) {
    foreach ($routesGroup as &$route) {
        $route[1] = ($route[1] === '') ? 'test' : 'test/' . $route[1];
        $route[2] = 'Test\\' . $route[2];
        $urls[] = url($route[1]);
    }
}

\App\Services\Session::set('test-routes', $urls);

return $routes;
