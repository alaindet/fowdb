<?php

function database()
{
	return \App\Database::getInstance();
}

function cached(string $request)
{
	return \App\Helpers::get($request);
}

function notify(string $message, string $type)
{
	\App\Services\Alert::set($message, $type);
}

function redirect(string $to = '/', array $params = [])
{
	\App\Redirect::to($to, $params);
}

function render(string $toRender): string
{
	return \App\Views\Card\CardText::render($toRender);
}

function collapse(): string
{
	return implode('', func_get_args());
}

function url(string $page = '', array $params = []): string
{
	return \App\Redirect::url($page, $params);
}

function logHtml($x = null, $title = ''): string
{
	return \App\Utils\Logger::html($x, $title);
}

function view(
	string $title = null,
	string $path = null,
	array $options = null,
	array $vars = null,
	bool $minimize = true
)
{
	return \App\Views\Page::build($title, $path, $options, $vars, $minimize);
}

function asset(string $path, string $type = 'any'): string
{
	$version = [
		'any' => '20181012-1',
		'css' => '20181012-2',
		'js' => '20181012-1',
		'png' => '20181012-1'
	][$type];

	return $path.'?'.$version;
}

function fromRoot(string $path = ''): string
{
	return APP_ROOT . "/$path";
}
