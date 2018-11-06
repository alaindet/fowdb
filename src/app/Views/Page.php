<?php

namespace App\Views;

use App\Exceptions\TemplateException;
use App\Views\TinyHtmlMinifier\TinyMinify;
use App\Services\OpenGraphProtocol\OpenGraphProtocol;
use App\Services\OpenGraphProtocol\OpenGraphProtocolImage;

/**
 * This class renders the HTML to be shown to the user
 * 
 * Conventions:
 * - $variables are bound to the template page to be rendered
 * - $options are bound to the main container template and control the container
 *   Ex.: What dependencies to load, what page script to load, OGP tags,
 *   the page title
 */
class Page
{
    private $title;
    private $variables = [];
    private $options = [];
    private $template;
    private $mainTemplate;
    private $minify = true;

    /**
     * Sets some default values
     */
    public function __construct()
    {
        $this->title = config('app.name');
        $this->mainTemplate = path_views('layout/main.tpl.php');
    }

    /**
     * Sets the title
     *
     * @return Page
     */
    public function title(string $title): Page
    {
        $this->title = $title . ' ~ ' . $this->title;

        return $this;
    }

    /**
     * Sets the variables accessible to the page script and its includes only
     *
     * @param array $variables
     * @return Page
     */
    public function variables(array $variables): Page
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Sets the variables accessible to the main container template only
     *
     * @param array $options
     * @return Page
     */
    public function options(array $options): Page
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Sets the template to render
     *
     * @param string $name
     * @return Page
     */
    public function template(string $name): Page
    {
        $this->template = path_views("{$name}.tpl.php");

        return $this;
    }

    /**
     * Sets the minification flag
     *
     * @param boolean $minify
     * @return Page
     */
    public function minify(bool $minify): Page
    {
        $this->minify = $minify;

        return $this;
    }

    /**
     * Instantiates the Open Graph Protocol class and updates tags, if passed
     *
     * @return OpenGraphProtocol
     */
    private function openGraphProtocol(): OpenGraphProtocol
    {
        $ogp = OpenGraphProtocol::getInstance();

        // Set custom ogp:* tags
        if (isset($this->options['ogp'])) {

            $myOgp =& $this->options['ogp'];

			// Update title
            if (isset($myOgp['title'])) {
				$ogp->title($myOgp['title']);
			}

			// Update URL
			if (isset($myOgp['url'])) {
				$ogp->url($myOgp['url']);
			}

			// Update image
			if (isset($myOgp['image'])) {

                $image = (new OpenGraphProtocolImage)
                    ->url( $myOgp['image']['url'] ?? config('ogp.image') )
                    ->mimeType( config('ogp.image.type') )
                    ->width( config('ogp.image.width') )
                    ->height( config('ogp.image.height') )
                    ->alt( $myOgp['image']['alt'] ?? config('app.name') );

				$ogp->image($image);
			}
        }
        
        return $ogp;
    }

    private function renderTemplate(
        string $template,
        array $variables = null
    ): string
    {
        // Bind variables to this template only
        if (!empty($variables)) {
            foreach ($variables as $name => $value) {
                $$name = $value;
            }
		}

        // Load and render page template as a string
        ob_start();
        require $template;
        return ob_get_clean();
    }

    /**
     * Render given template inside the main template
     *
     * @return string
     */
    public function render(): string
    {
        $content = $this->renderTemplate(
            $this->template,
            $this->variables
        );
        
        $defaultOptions = [
            'title' => $this->title,
            'ogp' => $this->openGraphProtocol(),
            'content' => $content,
            'dependencies' => [],
            'scripts' => [],
            'state' => []
        ];

        $this->options = array_merge($defaultOptions, $this->options);

        $html = $this->renderTemplate(
            $this->mainTemplate,
            $this->options
        );

        // Return raw HTML
        if (!$this->minify) {
            return $html;
        }

        return TinyMinify::html($html);
    }
}
