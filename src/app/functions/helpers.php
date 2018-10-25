<?php

// Imports
use \App\Legacy\Database as LegacyDatabase;
use \App\Http\Request\Input;

/**
 * Index:
 * 
 * SERVICES
 * ========
 * admin_level // LEGACY
 * cached // LEGACY
 * config
 * database_old // LEGACY
 * database // TO DO
 * input
 * lookup
 * notify // LEGACY
 * alert
 * redirect_old // LEGACY
 * redirect // TO DO
 * 
 * DIRECTORIES
 * ===========
 * path_cache
 * path_root
 * path_src
 * path_views
 * 
 * VIEW
 * ====
 * asset
 * collapse
 * csrf_token
 * logHtml
 * render
 * url_old // LEGACY
 * url // TO DO
 * view_old // LEGACY
 * view // TO DO
 */


// SERVICES -------------------------------------------------------------------

/**
 * LEGACY: Checks the authorization level of current user
 * 
 * 0: public (not logged)
 * 1: super admin
 * 2: judge
 *
 * @return int
 */
function admin_level(): int
{
	return \App\Legacy\Authorization::level();
}

/**
 * LEGACY: Gets cached data from App\Legacy\Helpers
 *
 * @param string $path
 * @return mixed string | array
 */
function cached(string $path)
{
    return \App\Legacy\Helpers::get($path);
}

/**
 * Returns configuration data
 *
 * @param string $name
 * @return mixed string | array
 */
function config(string $name)
{
	return (\App\Services\ Config::getInstance())->get($name);
}

/**
 * LEGACY: Returns the database singleton
 *
 * @return LegacyDatabase
 */
function database_old(): LegacyDatabase
{
    return LegacyDatabase::getInstance();
}

/**
 * TO DO: Return a new database instance
 * TEMPORARY: Mimics database_old()
 *
 * @return LegacyDatabase
 */
function database(): LegacyDatabase
{
    return LegacyDatabase::getInstance();
    
    return database_old();
}

/**
 * Returns the Input instance for accessing GET, POST and FILES parameters
 * 
 * @return Input
 */
function input(): Input
{
	return Input::getInstance();
}

/**
 * Reads and returns lookup data from the cache
 *
 * @param string $path Dot-separated path. Ex.: "rarities.id2code"
 * @return mixed string | array
 */
function lookup(string $path = null)
{
    return (\App\Services\Lookup\Lookup::getInstance())->get($path);
}

/**
 * Adds an alert to be shown. If redirect is used after this, it's shown
 * on the next request, otherwise it's shown on the current page
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function notify(string $message, string $type = null): void
{
	\App\Services\Alert::set($message, $type);
}

/**
 * Alias of notify()
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function alert(string $message, string $type = null): void
{
    notify($message, $type);
}

/**
 * Redirects to another URL, accepts array to parse as querystring
 *
 * @param string $to
 * @param array $params
 * @return void
 */
function redirect_old(string $to = null, array $params = []): void
{
    \App\Legacy\Redirect::to($to, $params);
}

/**
 * TO DO: Call new Redirect library
 * TEMPORARY: Mimics redirect_old()
 *
 * @param string $to
 * @param array $params
 * @return void
 */
function redirect(string $to = null, array $params = []): void
{
    redirect_old($to, $params);
}


// DIRECTORIES ----------------------------------------------------------------

/**
 * @param string Relative path to /src/cache/
 * @return string Absolute path
 */
function path_cache(string $path = null): string
{
	$dir = (\App\Services\Config::getInstance())->get('dir.cache');
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /
 * @return string Absolute path
 */
function path_root(string $path = null): string
{
	$dir = (\App\Services\Config::getInstance())->get('dir.root');
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /src/
 * @return string Absolute path
 */
function path_src(string $path = null): string
{
	$dir = (\App\Services\Config::getInstance())->get('dir.src');
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /src/resources/views/
 * @return string Absolute path
 */
function path_views(string $path = null): string
{
	$dir = (\App\Services\Config::getInstance())->get('dir.views');
	return isset($path) ? "{$dir}/{$path}" : $dir;
}


// VIEW -----------------------------------------------------------------------

/**
 * Builds the URL for any asset, appending querystrings to bust the cache
 *
 * @param string $path
 * @param string $type
 * @return string
 */
function asset(string $path, string $type = 'any'): string
{
	$config = \App\Services\Config::getInstance();
    
    $url = $config->get('app.url');

    $version = [
		'any' => $config->get('app.timestamp'),
		'css' => $config->get('app.timestamp.css'),
		'js'  => $config->get('app.timestamp.js'),
		'png' => $config->get('app.timestamp.img'),
		'jpg' => $config->get('app.timestamp.img'),
    ][$type];

    return "{$url}/{$path}?{$version}";
}

/**
 * Accepts a list of strings and collapses them in a single string.
 * Useful when building some HTML content for easy indentation
 * 
 * Ex.:
 * collapse(
 *   "<a href='{$someHref}'>",
 *     $someContent
 *   "</a>"
 * )
 *
 * @return string
 */
function collapse(): string
{
	return implode('', func_get_args());
}

/**
 * Prints an <input> element containing the anti-CSRF token
 *
 * @return string
 */
function csrf_token(): string
{
	return \App\Services\CsrfToken::formInput();
}

/**
 * Logs provided data on the page in a HTML-friendly format using .well elements
 * Useful for debugging data inside views
 *
 * @param mixed $data Can be any type of data
 * @param string $title Optional
 * @return string HTML-friendly log of provided data
 */
function logHtml($data, string $title = null): string
{
	return \App\Utils\Logger::html($data, $title);
}

/**
 * Render any text into an HTML-friendly format using FoWDB conventions
 *
 * @param string $toRender
 * @return string
 */
function render(string $toRender): string
{
	return \App\Views\Card\CardText::render($toRender);
}

/**
 * LEGACY: Builds a URL and returns it
 *
 * @param string $page
 * @param array $params
 * @return string
 */
function url_old(string $page = '', array $params = []): string
{
	return RedirectLegacy::url($page, $params);
}

/**
 * TO DO: Call new Redirect library
 * TEMPORARY: Mimics url_old()
 *
 * @param string $page
 * @param array $params
 * @return string
 */
function url(string $to = null, array $params = []): string
{
    return url_old($to, $params);
}

/**
 * Calls the page constructor
 *
 * @param string $title
 * @param string $path
 * @param array $options
 * @param array $vars
 * @param boolean $minimize
 * @return void
 */
function view_old(
	string $title = null,
	string $path = null,
	array $options = null,
	array $vars = null,
	bool $minimize = true
)
{
    return \App\Legacy\Page::build($title, $path, $options, $vars, $minimize);
}


/**
 * Calls the page constructor
 *
 * @param string $title
 * @param string $path
 * @param array $options
 * @param array $vars
 * @param boolean $minimize
 * @return void
 */
function view(
	string $title = null,
	string $path = null,
	array $options = null,
	array $vars = null,
	bool $minimize = true
)
{
    return view_old($title, $path, $options, $vars, $minimize);
}
