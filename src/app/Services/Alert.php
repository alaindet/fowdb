<?php

namespace App\Services;

class Alert
{
    private static $types = ['success', 'info', 'warning', 'danger'];

    /**
	 * Notify the user
	 *
	 * @param string Alert message
	 * @param string Alert element color
	 */
    public static function set($message, $type = 'info')
    {
		if (session_status() == PHP_SESSION_NONE) session_start();
		if (!in_array($type, self::$types)) $type = 'info';
		$_SESSION['notif'] = $message;
		$_SESSION['notif-type'] = $type;
	}
}
