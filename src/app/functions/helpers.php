<?php

// Imports
use \App\Services\Database\Database;
use \App\Http\Request\Input;
use \App\Services\Database\Statement\SqlStatement;
use \App\Legacy\Authorization as LegacyAuthorization;

/**
 * Index:
 * 
 * SERVICES
 * ========
 * admin_level // LEGACY
 * alert
 * auth
 * config
 * database // TO DO
 * dump
 * fd_divinity
 * input
 * lookup
 * redirect // TO DO
 * redirect_old // LEGACY
 * statement
 * 
 * DIRECTORIES
 * ===========
 * path_cache
 * path_data
 * path_public
 * path_src
 * path_views
 * 
 * VIEW
 * ====
 * asset
 * collapse
 * component
 * csrf_token
 * include_view
 * escape
 * log_html
 * render
 * url_old // LEGACY
 * url // TO DO
 * view_old // LEGACY
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
	return (LegacyAuthorization::getInstance())->level();
}

/**
 * Adds an alert to be shown. If redirect is used after this, it's shown
 * on the next request, otherwise it's shown on the current page
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function alert(string $message, string $type = null): void
{
    \App\Services\Alert::add($message, $type);
}

/**
 * LEGACY: Returns the authorization singleton
 *
 * @return LegacyAuthorization
 */
function auth(): LegacyAuthorization
{
	return LegacyAuthorization::getInstance();
}

/**
 * Returns configuration data
 *
 * @param string|array $name 
 * @return mixed string|null
 */
function config($name)
{
	$config = \App\Services\Config\Config::getInstance();

	if (is_array($name)) {
		return $config->getByKeys($name);
	} else {
		return $config->get($name);
	}
}

/**
 * Returns the database instance
 *
 * @return Database
 */
function database(): Database
{
    return Database::getInstance();
}

/**
 * Prints raw data to the screen as readable HTML and STOPS EXECUTION
 *
 * @param mixed $data Data to be dumped
 * @param string $title (Optional) Title to give to the log
 * @return void
 */
function dump($data, string $title = null): void
{
	$mode = config("app.mode");

	if ($mode === "web") {
		echo \App\Utils\Logger::html($data, $title);
		die();	
	} elseif ($mode === "cli") {
		echo \App\Utils\Logger::cli($data, $title);
		die();
	}
}

function fd_divinity($divinity): string
{
	$infinity = config('game.divinity.infinity');
	return (intval($divinity) === $infinity) ? "&infin;" : strval($divinity);
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
 * Redirects to given URI, accepts an array of values to build the query string
 *
 * @param string $to
 * @param array $params
 * @return void
 */
function redirect(string $uri = '', array $qs = []): void
{
	\App\Http\Response\Redirect::to($uri, $qs);
}

/**
 * Returns a database statement based on the passed type
 *
 * @param string $type
 * @return SqlStatement
 */
function statement(string $type): SqlStatement
{
	$class = [
		'select' => \App\Services\Database\Statement\SelectSqlStatement::class,
		'insert' => \App\Services\Database\Statement\InsertSqlStatement::class,
		'update' => \App\Services\Database\Statement\UpdateSqlStatement::class,
		'delete' => \App\Services\Database\Statement\DeleteSqlStatement::class,
	][$type];

	$statement = new $class;

	return $statement;
}


// DIRECTORIES ----------------------------------------------------------------

/**
 * @param string Relative path to {src}/data/cache/
 * @return string Absolute path
 */
function path_cache(string $path = null): string
{
	$dir = config("dir.cache");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to {src}/data/
 * @return string Absolute path
 */
function path_data(string $path = null): string
{
	$dir = config("dir.data");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

function path_public(string $path = null): string
{
	$dir = config("dir.public");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to {src}
 * @return string Absolute path
 */
function path_src(string $path = null): string
{
	$dir = config("dir.src");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to {src}/resources/views/
 * @return string Absolute path
 */
function path_views(string $path = null): string
{
	$dir = config("dir.views");
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
function asset(string $path, string $type = "generic"): string
{
	$timestampKey = [
		"generic"    => "asset.timestamp.generic",
		"css"        => "asset.timestamp.css",
		"js"         => "asset.timestamp.js",
		"javascript" => "asset.timestamp.js",
		"png"        => "asset.timestamp.img",
		"jpeg"       => "asset.timestamp.img",
		"jpg"        => "asset.timestamp.img",
	][$type];

	$config = config(["app.url", $timestampKey]);
	$url = &$config["app.url"];
	$version = &$config[$timestampKey];

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
 * Instantiates a view component, renders it using provided data and
 * returns its HTML rendering as a string
 *
 * @param string $name Name of the component, as in App\Views\Components
 * @param array $state The state to set, as associative array
 * @return string HTML rendering of the component
 */
function component(string $name, array $state = null): string
{
	$class = \App\Views\Components::$components[$name] ?? null;

	// ERROR: Component name doesn't exist
	if ($class === null) {
		throw new \App\Exceptions\ViewsComponentException(
			"Missing component \"{$name}\""
		);
	}

	// Simple component (no logic, optional state)
	if ($class === \App\Views\Components::SIMPLE_COMPONENT) {
		return include_view("components/{$name}", $state);
	}

	// Return rendered HTML component
	$component = new $class();
	$component->setState(function () use ($state) { return $state; });
	return $component->render();
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
 * Includes a view file, returns it as a string
 *
 * @param string $path
 * @return string
 */
function include_view(string $path, array $variables = null): string
{
	// Bind variables to this template only
	if (!empty($variables)) {
		foreach ($variables as $name => $value) {
			$$name = $value;
		}
	}

	// Load and render this template as a string
	ob_start();
	include path_views("{$path}.tpl.php");
	return ob_get_clean();
}

/**
 * Escapes HTML characters
 * Doc: http://www.php.net/manual/en/function.htmlspecialchars.php
 *
 * @param string $string
 * @return string Escaped sequence
 */
function escape(string $string): string
{
	return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Logs provided data on the page in a HTML-friendly format using .well elements
 * Useful for debugging data inside views
 *
 * @param mixed $data Can be any type of data
 * @param string $title Optional
 * @return string HTML-friendly log of provided data
 */
function log_html($data, string $title = null): string
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
	return \App\Legacy\Redirect::url($page, $params);
}

/**
 * Outputs an absolute URL
 *
 * @param string $page
 * @param array $params
 * @return string
 */
function url(string $to = null, array $params = []): string
{
	return \App\Utils\Uri::build($to, $params);
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
): string
{
    return \App\Legacy\Page::build($title, $path, $options, $vars, $minimize);
}

/**
 * Returns a compiled Twig page
 *
 * @param string $viewPath Path from the view dir, no extension Ex.: pages/card/index
 * @param array $variables Variables to bind to the view
 * @return string HTML output of the page
 */

/**
 * Returns a rendered page to be output
 *
 * @param string $viewPath Relative to {src}/resorces/views/, no extension
 * @param string $title Title of the page
 * @param array $variables Variables to be used to render the template
 * @param boolean $minify Minify the HTML output
 * @return string Final HTML for the page
 */
function view(
	string $viewPath = null,
	string $title = null,
	array $variables = null,
	bool $minify = true
): string
{
	return (new \App\Views\Page)
		->template($viewPath)
		->title($title)
		->variables($variables)
		->minify($minify)
		->render();
}
