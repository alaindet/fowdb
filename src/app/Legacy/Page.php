<?php

namespace App\Legacy;

use App\Legacy\Database;
use App\Views\TinyHtmlMinifier\TinyMinify;
use App\Services\OpenGraphProtocol\OpenGraphProtocol;
use App\Services\OpenGraphProtocol\OpenGraphProtocolImage;

class Page
{
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
	 * @return void
	 */
	public static function build(
		string $title = null,
		string $path = null,
		array $options = null,
		array $vars = null,
		bool $minimize = true
	): string
	{
		if (isset($title)) $page_title = $title;
		$scriptPath = path_root($path);

		// Open Graph Protocol ------------------------------------------------
		$ogp = OpenGraphProtocol::getInstance();

		if (isset($options['ogp'])) {

			// Update title
			if (isset($options['ogp']['title'])) {
				$ogp->title($options['ogp']['title']);
			}

			// Update URL
			if (isset($options['ogp']['url'])) {
				$ogp->url($options['ogp']['url']);
			}

			// Update image
			if (isset($options['ogp']['image'])) {
				$_image =& $options['ogp']['image'];
				$ogp->image(
					(new OpenGraphProtocolImage())
						->url( $_image['url'] ?? config('ogp.image') )
						->mimeType( config('ogp.image.type') )
						->width( config('ogp.image.width') )
						->height( config('ogp.image.height') )
						->alt( $_image['alt'] ?? config('app.name') )
				);
			}
		}

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

		// Store generated content and clear the buffer
		$pageContent = ob_get_contents();
		ob_clean();

		// Load the main template
		require path_root('src/resources/views/old/layout/main.php');

		// Get the final HTML output
		$html = ob_get_clean();

		// Minimize the output
		if ($minimize) {
			return TinyMinify::html($html);
		}
		
		// Do not minimize the output (preserves <pre></pre> tags)
		else {
			return $html;
		}
	}
}
