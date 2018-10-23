<?php

use \App\Legacy\Authorization;
use \App\Legacy\Database;
use \App\Legacy\Helpers as Cache; // Aliasing here!
use \App\Services\Alert;
use \App\Legacy\Redirect as RedirectLegacy;
use \App\Views\Card\CardText;
use \App\Utils\Logger;
use \App\Legacy\Page as PageLegacy;
use \App\Services\FileSystem;
use \App\Services\CsrfToken;
use \App\Http\Request\Input;
use \App\Services\Config;

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
	RedirectLegacy::to($to, $params);
}

/**
 * Build a URL and returns it. Legacy: used with old querystring-routes
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
	return PageLegacy::build($title, $path, $options, $vars, $minimize);
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
	$config = Config::getInstance();

	$version = [
		'any' => $config->get('app.timestamp'),
		'css' => $config->get('app.timestamp.css'),
		'js'  => $config->get('app.timestamp.js'),
		'png' => $config->get('app.timestamp.img'),
		'jpg' => $config->get('app.timestamp.img'),
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
	$root = (Config::getInstance())->get('dir.root');
	return isset($path) ? "{$root}/{$path}" : $root;
}

/**
 * Returns an absolute path by providing a path relative to /src
 *
 * @param string $path (optional)
 * @return string
 */
function path_src(string $path = null): string
{
	$dir = (Config::getInstance())->get('dir.src');
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * Returns an absolute path by providing a path relative to /src/resources/views
 *
 * @param string $path (optional)
 * @return string
 */
function path_views(string $path = null): string
{
	$dir = (Config::getInstance())->get('dir.views');
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * Returns an absolute path by providing a path relative to /src/cache
 *
 * @param string $path (optional)
 * @return string
 */
function path_cache(string $path = null): string
{
	$dir = (Config::getInstance())->get('dir.cache');
	return isset($path) ? "{$dir}/{$path}" : $dir;
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

function config(string $name): string
{
	return (Config::getInstance())->get($name);
}

/**
 * To be continued...
 *
 * @return string
 */
function url(): string
{
	return '';
}
