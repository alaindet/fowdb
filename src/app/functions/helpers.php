<?php

use App\Base\ORM\Interfaces\RepositoryInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Exceptions\ViewsComponentException;
use App\Http\Request\Input;
use App\Http\Response\Redirect;
use App\Legacy\Authorization as LegacyAuthorization;
use App\Services\Alert;
use App\Services\Configuration\Configuration;
use App\Services\CsrfToken;
use App\Services\Database\Database;
use App\Services\Database\StatementManager\StatementManager;
use App\Services\Database\Statement\SqlStatement;
use App\Services\Lookup\Lookup;
use App\Utils\Logger;
use App\Utils\Strings;
use App\Utils\Uri;
use App\Views\Card\CardText;
use App\Views\Components;
use App\Views\Page;

/**
 * List of helper functions
 * Any helper function fd_starts with "fd_"
 * 
 * SERVICES
 * ========
 * fd_alert
 * fd_auth
 * fd_config
 * fd_database
 * fd_dump
 * fd_input
 * fd_lookup
 * fd_redirect
 * fd_repository
 * fd_statement
 * 
 * DIRECTORIES
 * ===========
 * fd_path_cache
 * fd_path_data
 * fd_path_root
 * fd_path_src
 * fd_path_views
 * 
 * VIEW
 * ====
 * fd_asset
 * fd_component
 * fd_csrf_token
 * fd_include_view
 * fd_escape
 * fd_log_html
 * fd_render
 * fd_url
 * 
 */


// SERVICES -------------------------------------------------------------------

/**
 * Adds an alert to be shown. If redirect is used after this, it's shown
 * on the next request, otherwise it's shown on the current page
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function fd_alert(string $message, string $type = null): void
{
    Alert::add($message, $type);
}

/**
 * LEGACY: Returns the authorization singleton
 *
 * @return LegacyAuthorization
 */
function fd_auth(): LegacyAuthorization
{
	return LegacyAuthorization::getInstance();
}

/**
 * Returns or sets configuration data
 *
 * @param string $name
 * @param any $value
 * @return mixed null|string|array
 */
function fd_config(string $name = null, $value = null)
{
	$config = Configuration::getInstance();

	// Return configuration service
	if ($name === null) {
		return $config;
	}

	// Return configuration value
	if ($value === null) {
		return (Configuration::getInstance())->get($name);
	}

	// Set value on configurations
	(Configuration::getInstance())->set($name, $value);
}

/**
 * Returns the database instance
 *
 * @return Database
 */
function fd_database(): Database
{
    return Database::getInstance();
}

/**
 * Prints raw data to the screen as readable HTML and STOPS EXECUTION
 *
 * @param mixed $data Data to be dumped
 * @param string $title (Optional) Title to give to the log
 * @param bool $wrap (Optional) Wraps the line
 * @return void
 */
function fd_dump($data, string $title = null, bool $wrap = false): void
{
	ob_end_clean();
	echo Logger::html($data, $title, $wrap);
	die();
}

/**
 * Returns the Input instance for accessing GET, POST and FILES parameters
 * 
 * @return Input
 */
function fd_input(): Input
{
	return Input::getInstance();
}

/**
 * Reads and returns lookup data from the cache
 *
 * @param string $path Dot-separated path. Ex.: "rarities.id2code"
 * @return mixed string | array
 */
function fd_lookup(string $path = null)
{
    return (Lookup::getInstance())->get($path);
}

/**
 * Redirects to given URI, accepts an array of values to build the query string
 *
 * @param string $to
 * @param array $params
 * @return void
 */
function fd_redirect(string $uri = "", array $qs = []): void
{
	Redirect::to($uri, $qs);
}

/**
 * Returns an entity repository instance from an entity class name
 *
 * @param string $entityClass
 * @return RepositoryInterface
 */
function fd_repository(string $entityClass): RepositoryInterface
{
	return EntityManager::getRepository($entityClass);
}

/**
 * Returns a database statement based on the passed type
 *
 * @param string $type
 * @return SqlStatement
 */
function fd_statement(string $type): SqlStatement
{
	return StatementManager::new($type);
}


// DIRECTORIES ----------------------------------------------------------------

/**
 * @param string Relative path to /src/data/cache/
 * @return string Absolute path
 */
function fd_path_cache(string $path = null): string
{
	$dir = (Configuration::getInstance())->get("dir.cache");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /src/data/
 * @return string Absolute path
 */
function fd_path_data(string $path = null): string
{
	$dir = (Configuration::getInstance())->get("dir.data");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /
 * @return string Absolute path
 */
function fd_path_root(string $path = null): string
{
	$dir = (Configuration::getInstance())->get("dir.root");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /src/
 * @return string Absolute path
 */
function fd_path_src(string $path = null): string
{
	$dir = (Configuration::getInstance())->get("dir.src");
	return isset($path) ? "{$dir}/{$path}" : $dir;
}

/**
 * @param string Relative path to /src/resources/views/
 * @return string Absolute path
 */
function fd_path_views(string $path = null): string
{
	$dir = (Configuration::getInstance())->get("dir.views");
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
function fd_asset(string $path, string $type = "any"): string
{
	$config = Configuration::getInstance();
    
	$url = $config->get("app.url");

	// Bypass query string if already present
	if (strpos($path, "?")) return "{$url}/{$path}";

    $version = [
		"any" => $config->get("app.timestamp"),
		"css" => $config->get("app.timestamp.css"),
		"js"  => $config->get("app.timestamp.js"),
		"png" => $config->get("app.timestamp.img"),
		"jpg" => $config->get("app.timestamp.img"),
    ][$type];

    return "{$url}/{$path}?{$version}";
}

/**
 * Instantiates a view component, renders it using provided data and
 * returns its HTML rendering as a string
 *
 * @param string $name Name of the component, as in App\Views\Components
 * @param array $state The state to set, as associative array
 * @return string HTML rendering of the component
 */
function fd_component(string $name, array $state = null): string
{
	$class = Components::$components[$name] ?? null;

	// ERROR: Component name doesn't exist
	if ($class === null) {
		throw new ViewsComponentException("Missing component \"{$name}\"");
	}

	// Simple component (no logic, optional state)
	if ($class === Components::SIMPLE_COMPONENT) {
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
function fd_csrf_token(): string
{
	return CsrfToken::formInput();
}

/**
 * Includes a view file, returns it as a string
 *
 * @param string $path
 * @return string
 */
function fd_include_view(string $path, array $__variables = null): string
{
	// Bind variables to this template only
	if (!empty($__variables)) {
		foreach ($__variables as $__name => $__value) {
			if (strpos($__name, '-')) {
                $__name = Strings::kebabToSnake($__name);
            }
			$$__name = $__value;
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
function fd_escape(string $string): string
{
	return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
}

/**
 * Logs provided data on the page in a HTML-friendly format using .well elements
 * Useful for debugging data inside views
 *
 * @param mixed $data Can be any type of data
 * @param string $title Optional
 * @param bool $wrap Wraps the line at the end of the viewport
 * @return string HTML-friendly log of provided data
 */
function fd_log_html($data, string $title = null, bool $wrap = false): string
{
	return Logger::html($data, $title, $wrap);
}

/**
 * Render any text into an HTML-friendly format using FoWDB conventions
 *
 * @param string $toRender
 * @return string
 */
function fd_render(string $toRender): string
{
	return CardText::render($toRender);
}

/**
 * Outputs an absolute URL
 *
 * @param string $page
 * @param array $params
 * @return string
 */
function fd_url(string $to = null, array $params = []): string
{
	return Uri::build($to, $params);
}

/**
 * Returns a rendered page to be output
 *
 * @param string $templatePath Relative to /src/resorces/views/, no extension
 * @param string $title Title of the page
 * @param array $variables Variables to be used to render the template
 * @param boolean $minify Minify the HTML output
 * @return string Final HTML for the page
 */
function fd_view(
	string $templatePath = null,
	string $title = null,
	array $variables = null,
	bool $minify = true
): string
{
	return (new Page)
		->template($templatePath)
		->title($title)
		->variables($variables)
		->minify($minify)
		->render();
}
