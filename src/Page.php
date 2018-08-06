<?php

namespace App;

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

	static function build ($title = null, $path = null, $options = null, $vars = null) {
		
		$pdo = \App\Database::getInstance(true);

		if (isset($title)) { $page_title = $title; }
		
		/*
		 * Check if a valid path have been passed,
		 * and use it or throw an exception
		 */
		if (isset($path) AND file_exists(APP_ROOT."/".$path)) {

			// Get content path
			$contentPath = APP_ROOT."/".$path;

			// EXPLANATION
			// Starting and stopping buffering needs to be done in order to
			// let the script add notifications and/or redirect the user

			// Start output buffering
			ob_start();
			// Re-define template variables to make them reusable inside the template
			if (!empty($vars)) {
			    for($i = 0, $keys = array_keys($vars), $len = count($keys); $i < $len; $i++) {
			        $name = $keys[$i];
			        $$name =& $vars[$name];
			    }
			}
			// Generate content into buffer (lets script add notifications)
			include $contentPath;
			// Get generated page content so far, erase buffered, stop buffering
			$pageContent = ob_get_clean();
			// Include page template and print content into it
			require APP_ROOT . '/_template/page.php';
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
