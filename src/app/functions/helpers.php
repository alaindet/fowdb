<?php

use \App\Legacy\Authorization;
use \App\Legacy\Database;
use \App\Legacy\Helpers as Cache; // Aliasing here!
use \App\Services\Alert;
use \App\Legacy\Redirect;
use \App\Views\Card\CardText;
use \App\Utils\Logger;
use \App\Legacy\Page as LegacyPage;
use \App\Services\FileSystem;
use \App\Services\CsrfToken;
use \App\Http\Request\Input;

/**
 * Checks the authorization level of the current user
 * 
 * 0: public (not logged)
 * 1: super admin
 * 2: judge
 *
 * @return int Authorization level
 */
function admin_level(): int
{
	return Authorization::level();
}

/**
 * Gets the database instance
 *
 * @return Database
 */
function database(): Database
{
	return Database::getInstance();
}

/**
 * Gets cached data
 *
 * @param string $request
 * @return any String | Array
 */
function cached(string $request)
{
	return Cache::get($request);
}

/**
 * Adds a notification for the next request
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function notify(string $message, string $type = null): void
{
	Alert::set($message, $type);
}

/**
 * Redirect the user to another URL, can pass querystring parameters
 *
 * @param string $to
 * @param array $params
 * @return void
 */
function redirect(string $to = '/', array $params = []): void
{
	Redirect::to($to, $params);
}

/**
 * Build a URL and returns it. Legacy: used with old querystring-routes
 *
 * @param string $page
 * @param array $params
 * @return string
 */
function url(string $page = '', array $params = []): string
{
	return Redirect::url($page, $params);
}

/**
 * Render any text into an HTML-friendly format using icons and styling
 *
 * @param string $toRender
 * @return string
 */
function render(string $toRender): string
{
	return CardText::render($toRender);
}

/**
 * Accepts a list of strings and collapses it in a single string.
 * Useful when building some HTML content into PHP for indentation
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
 * Logs stuff on the page in a HTML-friendly format using .well elements
 *
 * @param Any $x Can be anything
 * @param string $title Optional
 * @return string HTML-friendly log of any variable
 */
function logHtml($x = null, string $title = ''): string
{
	return Logger::html($x, $title);
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
	return LegacyPage::build($title, $path, $options, $vars, $minimize);
}

/**
 * Builds the URL for any asset, appending querystrings to bust the cache
 *
 * @param string $path
 * @param string $type
 * @return string
 */
function asset(string $path, string $type = 'any'): string
{
	$version = [
		'any' => APP_TIMESTAMP,
		'css' => APP_TIMESTAMP_CSS,
		'js'  => APP_TIMESTAMP_JS,
		'png' => APP_TIMESTAMP_IMG,
	][$type];

	return '/'.$path.'?'.$version;
}

/**
 * Returns an absolute path by providing a path relative to /
 *
 * @param string $path (optional)
 * @return string
 */
function path_root(string $path = null): string
{
	return isset($path) ? DIR_ROOT . "/{$path}" : DIR_ROOT;
}

/**
 * Returns an absolute path by providing a path relative to /src
 *
 * @param string $path (optional)
 * @return string
 */
function path_src(string $path = null): string
{
	return isset($path) ? DIR_SRC . "/{$path}" : DIR_SRC;
}

/**
 * Returns an absolute path by providing a path relative to /src/resources/views
 *
 * @param string $path (optional)
 * @return string
 */
function path_views(string $path = null): string
{
	return isset($path) ? DIR_VIEWS . "/{$path}" : DIR_VIEWS;
}

/**
 * Returns an absolute path by providing a path relative to /src/cache
 *
 * @param string $path (optional)
 * @return string
 */
function path_cache(string $path = null): string
{
	return isset($path) ? DIR_CACHE . "/{$path}" : DIR_CACHE;
}

/**
 * Returns a loaded file (usually an array)
 *
 * @return void
 */
function load_file(string $absolutePath)
{
	return FileSystem::loadFile($absolutePath);
}

/**
 * Prints an <input> element containing the anti-CSRF token
 *
 * @return string
 */
function csrf_token(): string
{
	return CsrfToken::formInput();
}

function input(): Input
{
	return Input::getInstance();
}
