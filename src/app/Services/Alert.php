<?php

namespace App\Services;

use App\Services\Session\Session;

class Alert
{
	/**
	 * Name of the session variable that stores alerts
	 */
	public const NAME = 'fowdb-alert';

	/**
	 * Alert types, based on Bootstrap 3
	 *
	 * @var array
	 */
  private static $types = [
		'success',
		'info',
		'warning',
		'danger'
	];

	/**
	 * Default alert type (light blue)
	 *
	 * @var string
	 */
	private static $defaultType = 'info';

  /**
	 * Adds an alert to the session, stacks alerts into the session
	 *
	 * @param string $message
	 * @param string $type
	 * @return void
	 */
  public static function add(string $message, string $type = null): void
  {
		$alert = self::build($message, $type);
		Session::add(self::NAME, $alert);
	}

	/**
	 * Sets an alert (overriding all previous ones)
	 *
	 * @param string $message
	 * @param string $type
	 * @return void
	 */
	public static function set(string $message, string $type = null): void
	{
		$alert = self::build($message, $type);
		Session::set(self::NAME, [$alert]);
	}

	/**
	 * Builds a custom array representing an alert
	 *
	 * @param string $message
	 * @param string $type
	 * @return array The alert object
	 */
	private static function build(string $message, string $type = null): array
	{
		if (!isset($type) || !in_array($type, self::$types)) {
			$type = self::$defaultType;
		}

		return [
			'message' => $message,
			'type' => $type
		];
	}

	/**
	 * Returns all current alerts from session
	 *
	 * @return array
	 */
	public static function get(): array
	{
		if (!Session::exists(self::NAME)) return [];
		return Session::pop(self::NAME);
	}

	/**
	 * Clears all previous alerts
	 *
	 * @return void
	 */
	public static function clear(): void
	{
		Session::delete(self::NAME);
	}
}
