<?php

use App\Services\Session\Session;

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

    ["GET", "", "HomeController", "index"],

    ["GET", "array/defaults", "ArraysController", "defaults"],
    ["GET", "array/to-object", "ArraysController", "toObject"],
    ["GET", "array/whitelist", "ArraysController", "whitelist"],
    ["GET", "array/whitelist-keys", "ArraysController", "whitelistKeys"],

    ["GET", "bitmask/flipped", "BitmaskController", "flipped"],

    ["GET", "components/form/button-checkbox", "ComponentsController", "buttonCheckbox"],
    ["GET", "components/form/button-checkboxes", "ComponentsController", "buttonCheckboxes"],
    ["GET", "components/form/button-dropdown", "ComponentsController", "buttonDropdown"],
    ["GET", "components/form/button-radio", "ComponentsController", "buttonRadio"],
    ["GET", "components/form/input-dropdown", "ComponentsController", "inputDropdown"],
    ["GET", "components/navigation/pagination", "ComponentsController", "pagination"],
    ["GET", "components/form/select-multiple", "ComponentsController", "selectMultiple"],
    ["GET", "components/form/select-submit", "ComponentsController", "selectSubmit"],

    ["GET", "collection", "CollectionController", "index"],
    ["GET", "collection/cluster-to-formats", "CollectionController", "clusterToFormats"],
    ["GET", "collection/format-to-clusters", "CollectionController", "formatToClusters"],
    ["GET", "collection/sort", "CollectionController", "sortCollection"],

    ["GET", "database/pagination", "DatabaseController", "pagination"],
    ["GET", "database/statement-merge", "DatabaseController", "statementMerge"],

    ["GET", "input/all", "InputController", "all"],
    ["GET", "input/exists/{key}", "InputController", "exists"],

    ["GET", "filesystem/render-with-vars", "FileSystemController", "renderWithVars"],
    ["GET", "filesystem/render-with-obj", "FileSystemController", "renderWithObj"],

    ["GET", "lookup", "LookupController", "index"],
    ["GET", "lookup/build", "LookupController", "buildAll"],
    ["GET", "lookup/build/{feature}", "LookupController", "build"],
    ["GET", "lookup/read", "LookupController", "readAll"], // Alias
    ["GET", "lookup/read/{feature}", "LookupController", "read"],

    ["GET", "orm/custom-collection", "OrmController", "customCollection"],
    ["GET", "orm/related/1-n", "OrmController", "relatedOneToMany"],
    ["GET", "orm/related/n-1", "OrmController", "relatedManyToOne"],
    ["GET", "orm/related/n-n", "OrmController", "relatedManyToMany"],

    ["GET", "types/array-to-json", "TypesController", "arrayToJson"],
    ["GET", "types/array-to-object", "TypesController", "arrayToObject"],
    ["GET", "types/json-to-array", "TypesController", "jsonToArray"],
    ["GET", "types/json-to-object", "TypesController", "jsonToObject"],
    ["GET", "types/object-to-array", "TypesController", "objectToArray"],
    ["GET", "types/object-to-json", "TypesController", "objectToJson"],

    ["GET", "validate/empty", "ValidationController", "emptyRule"],
    ["GET", "validate/enum", "ValidationController", "enumRule"],
    ["GET", "validate/exists", "ValidationController", "existsRule"],
    ["GET", "validate/is", "ValidationController", "isRule"],
    ["GET", "validate/match", "ValidationController", "matchRule"],
    ["GET", "validate/numbers", "ValidationController", "numbersRule"],
    ["GET", "validate/required", "ValidationController", "requiredRule"],

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
        $url = &$route[1];
        $controller = &$route[2];
        $url = ($url === "") ? "test" : "test/{$url}";
        $controller = "Test\\{$controller}";
        $urls[] = $url;
    }
}

Session::set("test-routes" , $urls);

return $routes;
