<?php

namespace App\Services;

use App\Services\Session;

class Alert
{
	public const NAME = 'fowdb-alert';

    private static $types = [
		'success',
		'info',
		'warning',
		'danger'
	];

    /**
	 * Notify the user
	 *
	 * @param string Alert message
	 * @param string Alert element color
	 */
    public static function add(string $message, string $type = null)
    {
		// Use default type if needed
		if (!isset($type) || !in_array($type, self::$types)) $type = 'info';

		$alert = compact('message', 'type');

		Session::add(self::NAME, $alert);
	}

	public static function get(): array
	{
		if (!Session::exists(self::NAME)) return [];

		return Session::pop(self::NAME);
	}
}
