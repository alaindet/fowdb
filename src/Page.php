<?php

namespace App;

use App\Database;
use App\TinyHtmlMinifier\TinyMinify;

/**
 * This function generates a page
 *
 * Since the pages are quite the same except for content
 * and some optional CSS and JavaScript files,
 * you can pass an object to define what files to use
 * and what the content path and page title are
 *
 * @param string $title Title of the page
 * @param string $path Path where to get page content
 * @param array $options Array of options to be loaded (CSS, custom JS, JS libraries...)
 * @param array $vars Assoc array of template variables, where key is the name and value is the value 
 * @return mixed True if page was created, Exception if not
 */
class Page {

	static function build ($title = null, $path = null, $options = null, $vars = null)
	{
		if (isset($title)) {
			$page_title = $title;
		}

		$scriptPath = APP_ROOT . "/" . $path;;

		$pdo = Database::getInstance(true);
		
		if (isset($path) && file_exists($scriptPath))
		{
			// EXPLANATION
			// Starting and stopping buffering needs to be done in order to
			// let the script add notifications and/or redirect the user

			// Start output buffering
			ob_start();

			// Bind variables to templates
			if (!empty($vars)) {
				$keys = array_keys($vars);
			    for ($i = 0, $len = count($keys); $i < $len; $i++) {
			        $name = $keys[$i];
			        $$name =& $vars[$name];
			    }
			}
			
			// Execute the script and generate some content into the buffer
			require $scriptPath;

			// Read page content into a variable and clean it all
			$pageContent = ob_get_contents();
			ob_clean();

			// Build the entire template using previous content
			require APP_ROOT . '/_template/page.php';

			// Get content from buffer, close buffer, minify content
			echo TinyMinify::html(ob_get_clean());
		}
		else {
			throw new \Exception("No path provided or file doesn't exist at provided path.");
		}
		
		/*
		 * Everything went fine
		 */
		return true;
	}
}
