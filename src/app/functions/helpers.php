<?php

use App\Exceptions\ViewsComponentException;
use App\Services\Configuration\Configuration;
use App\Services\CsrfToken;
use App\Services\Database\Database;
use App\Services\Database\StatementManager\StatementManager;
use App\Services\Database\Statement\SqlStatement;
use App\Services\Lookup\Lookup;
use App\Utils\Logger;
use App\Utils\Paths;
use App\Utils\Strings;
use App\Utils\Uri;
use App\Views\Entities\Card\CardText;
use App\Views\Components;
use App\Views\Page\Page;
use App\Legacy\Authorization as LegacyAuthorization;
use App\Http\Request\Input\InputManager;
use App\Views\Component\ComponentManager;

/**
 * List of helper functions
 * Any helper function fd_starts with "fd_"
 * 
 * SERVICES
 * ========
 * fd_auth
 * fd_config
 * fd_database
 * fd_dump
 * fd_input
 * fd_lookup
 * fd_statement
 * 
 * VIEW
 * ====
 * fd_asset
 * fd_component
 * fd_csrf_token
 * fd_include_template
 * fd_escape
 * fd_log_html
 * fd_render
 * fd_url
 */


// SERVICES -------------------------------------------------------------------

/**
 * LEGACY: Returns the authorization singleton
 *
 * @return LegacyAuthorization
 */
function fd_auth(): LegacyAuthorization
{
	return LegacyAuthorization::getInstance();
}

function fd_config(string $key)
{
	return (Configuration::getInstance())->get($key);
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
 * @param string $method "GET", "POST" or "FILES"
 * @return InputObject|object
 */
function fd_input(): InputManager
{
	return InputManager::getInstance();
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
 * Returns a database statement based on the passed type
 *
 * @param string $type
 * @return SqlStatement
 */
function fd_statement(string $type): SqlStatement
{
	return StatementManager::new($type);
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
 * returns its HTML output as a string
 *
 * @param string $name Name of the component, ex.: "form/button-checkbox"
 * @param object $input Optional
 * @return string HTML rendering of the component
 */
function fd_component(string $name, object $input = null): string
{
	return ComponentManager::renderComponent($name, $input);
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
function fd_include_template(string $path, array $__variables = null): string
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
	include Paths::inTemplatesDir("{$path}.tpl.php");
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
