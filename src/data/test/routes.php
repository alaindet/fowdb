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
 | - Run the "captcha" middleware, but *NOT* the "token" middleware
 | [
 |     "POST",
 |     "test/update/{id}",
 |     "TestController",
 |     "update",
 |     ["d" => "[0-9]+"],
 |     ["!token","captcha"]
 | ]
 |
 | PLEASE NOTE:
 | - All routes are prefixed with "test/" (ex.: foo => test/foo)
 | - All controllers are prefixed "Test\\" (they must reside inside Test dir)
 |
 | ----------------------------------------------------------------------------
 */

/**
 * Public routes --------------------------------------------------------------
 * 
 * Anyone can access
 */
$public = [

    // Home
    ["GET", "", "HomeController", "index"],

    // View components
    ["GET", "button-checkboxes", "ComponentsController", "buttonCheckboxes"],
    ["GET", "button-checkbox", "ComponentsController", "buttonCheckbox"],
    ["GET", "button-dropdown", "ComponentsController", "buttonDropdown"],
    ["GET", "button-radio", "ComponentsController", "buttonRadio"],
    ["GET", "input-dropdown", "ComponentsController", "inputDropdown"],
    ["GET", "select-multiple", "ComponentsController", "selectMultiple"],
    ["GET", "pagination", "ComponentsController", "pagination"],

    // Array utilities
    ["GET", "array-whitelist", "UtilsController", "arrayWhitelist"],
    ["GET", "array-whitelist-keys", "UtilsController", "arrayWhitelistKeys"],
    ["GET", "array-defaults", "UtilsController", "arrayDefaults"],
    ["GET", "array-to-object", "UtilsController", "arrayToObject"],

    // Collection utilities
    ["GET", "collection", "CollectionController", "index"],
    ["GET", "collection/sort", "CollectionController", "sortCollection"],
    ["GET", "collection/format-to-clusters",
        "CollectionController", "formatToClusters"],
    ["GET", "collection/cluster-to-formats",
        "CollectionController", "clusterToFormats"],

    // Database
    ["GET", "database/pagination", "DatabaseController", "pagination"],
    ["GET", "database/statement-merge", "DatabaseController", "statementMerge"],

    // ORM
    ["GET", "orm/related/1-n", "OrmController", "relatedOneToMany"],
    ["GET", "orm/related/n-1", "OrmController", "relatedManyToOne"],
    ["GET", "orm/related/n-n", "OrmController", "relatedManyToMany"],
    ["GET", "orm/custom-collection", "OrmController", "customCollection"],

    // Lookup
    ["GET", "lookup", "LookupController", "index"],
    ["GET", "lookup/read", "LookupController", "readAll"], // Alias
    ["GET", "lookup/read/{feature}", "LookupController", "read"],
    ["GET", "lookup/build", "LookupController", "buildAll"],
    ["GET", "lookup/build/{feature}", "LookupController", "build"],

    // Cards
    ["GET", "cards/properties/html", "CardsController", "propsHtml"],
    ["GET", "cards/properties/html/{code}", "CardsController", "propsHtml"],
    ["GET", "cards/types-list", "CardsController", "typesList"],

    // Input
    ["GET", "input/all", "InputController", "all"],
    ["GET", "input/exists/{key}", "InputController", "exists"],

    // Type conversion
    ["GET", "types/array-to-object", "TypesController", "arrayToObject"],
    ["GET", "types/array-to-json", "TypesController", "arrayToJson"],
    ["GET", "types/object-to-array", "TypesController", "objectToArray"],
    ["GET", "types/object-to-json", "TypesController", "objectToJson"],
    ["GET", "types/json-to-array", "TypesController", "jsonToArray"],
    ["GET", "types/json-to-object", "TypesController", "jsonToObject"],

    // Validation service
    ["GET", "validate/empty", "ValidationController", "emptyRule"],
    ["GET", "validate/exists", "ValidationController", "existsRule"],
    ["GET", "validate/required", "ValidationController", "requiredRule"],
    ["GET", "validate/is", "ValidationController", "isRule"],
    ["GET", "validate/numbers", "ValidationController", "numbersRule"],
    ["GET", "validate/enum", "ValidationController", "enumRule"],
    ["GET", "validate/match", "ValidationController", "matchRule"],

    // Bitmasks
    ["GET", "bitmask/flipped", "BitmaskController", "flipped"],

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
    "public" => $public,
    "user" => $user,
    "admin" => $admin,
    "judge" => $judge
];

// Prefix routes and controllers with test/ and Test\\
$urls = [];
foreach ($routes as $role => &$routesGroup) {
    foreach ($routesGroup as &$route) {
        $route[1] = ($route[1] === "") ? "test" : "test/" . $route[1];
        $route[2] = "Test\\" . $route[2];
        $urls[] = fd_url($route[1]);
    }
}

\App\Services\Session\Session::set("test-routes", $urls);

return $routes;
