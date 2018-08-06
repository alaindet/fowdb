<?php

namespace App;

class Input {

	/**
	 * Checks if input data exists
	 *
	 * If label is provided, it checks if a value with that name exists,
	 * Otherwise checks is ANY input exists
	 *
	 * @param string $label If provided, checks for specific input value with that name
	 * @param string $type Select $_POST with 'post' or $_GET with 'get'
	 * @return bool TRUE if input exists, FALSE if not
	 */
	public static function exists ($label = null, $type = 'post') {

		// Check if a label was passed
		if (isset($label)) {

			// Distinguish between POST and GET methods
			switch ($type) {

				// POST
				case 'post':
					return isset($_POST[$label]) ? true : false;
					break;

				// GET
				case 'get':
					return isset($_GET[$label]) ? true : false;
					break;

				// Return error
				default:
					return false;
					break;
			}
		}
		// Check if ANY input exists
		else {

			// Distinguish between POST and GET methods
			switch ($type) {

				// POST
				case 'post':
					return !empty($_POST) ? true : false;
					break;

				// GET
				case 'get':
					return !empty($_GET) ? true : false;
					break;

				// Return error
				default:
					return false;
					break;
			}
		}
	}


	/**
	 * Returns input from $_POST or $_GET with name of $item
	 *
	 * @param string $item Label of requested data
	 * @return mixed Whichever data was passed, empty string if label doesn't exist
	 */
	public static function get ($item) {

		// Check if label exists in $_POST array
		if (isset($_POST[$item])) {

			// Return item from $_POST
			return $_POST[$item];
		}

		// If not, then check if label exists in $_GET array
		else if (isset($_GET[$item])) {

			// Return item from $_GET
			return $_GET[$item];
		}

		// If not again, return empty string anyway
		else {
			return '';
		}
	}
}
