<?php

namespace App\Views\Page;

use App\Services\Configuration\Configuration;
use App\Services\OpenGraphProtocol\OpenGraphProtocol;
use App\Services\OpenGraphProtocol\OpenGraphProtocolImage;
use App\Utils\Paths;
use App\Views\Page\PageInterface;
use App\Views\Page\TinyHtmlMinifier\TinyMinify;

/**
 * This class renders the HTML to be shown to the user
 * 
 * Conventions:
 * - $variables are bound to the template page to be rendered
 * - $options are bound to the main container template and control the container
 *   Ex.: What dependencies to load, what page script to load, OGP tags,
 *   the page title
 */
class Page implements PageInterface
{
    private $config;

    private $title;
    private $variables = [];
    private $options = [];
    private $template;
    private $mainTemplate;
    private $minify = false;

    /**
     * Sets some default values
     */
    public function __construct()
    {
        $this->config = Configuration::getInstance();
        $this->title = $this->config->get("app.name");
        $this->mainTemplate = Paths::inTemplatesDir("layout/main.tpl.php");
    }

    /**
     * Sets the title
     *
     * @return PageInterface
     */
    public function title(string $title): PageInterface
    {
        $this->title = "{$title} ~ {$this->title}";

        return $this;
    }

    /**
     * Sets the variables accessible to the page script and its includes only
     *
     * @param array $variables
     * @return PageInterface
     */
    public function variables(array $variables): PageInterface
    {
        $this->variables = $variables;

        return $this;
    }

    /**
     * Sets the variables accessible to the main container template only
     *
     * @param array $options
     * @return PageInterface
     */
    public function options(array $options): PageInterface
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Sets the template to render
     *
     * @param string $name
     * @return PageInterface
     */
    public function template(string $name): PageInterface
    {
        $this->template = Paths::inTemplatesDir("{$name}.tpl.php");

        return $this;
    }

    /**
     * Sets the minification flag
     *
     * @param boolean $minify
     * @return PageInterface
     */
    public function minify(bool $minify): PageInterface
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

        // Use current title by default
        $ogp->title($this->title);

        // Set custom ogp:* tags
        if (isset($this->options["ogp"])) {

            $myOgp = &$this->options["ogp"];

			// Update title
            if (isset($myOgp["title"])) {
				$ogp->title($myOgp["title"]);
			}

			// Update URL
			if (isset($myOgp["url"])) {
				$ogp->url($myOgp["url"]);
			}

			// Update image
			if (isset($myOgp["image"])) {

                $defaultUrl = $this->config->get("ogp.image");
                $defaultAlt = $this->config->get("app.name");
                $image = (new OpenGraphProtocolImage)
                    ->url($myOgp["image"]["url"] ?? $defaultUrl)
                    ->mimeType($this->config->get("ogp.image.type"))
                    ->width($this->config->get("ogp.image.width"))
                    ->height($this->config->get("ogp.image.height"))
                    ->alt($myOgp["image"]["alt"] ?? $defaultAlt);

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
        ob_start("ob_gzhandler");
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
        
        // Prefixed variable names should be be altered by client script
        $defaultOptions = [
            "dependencies" => [],
            "fd_content"   => $content,
            "fd_ogp"       => $this->openGraphProtocol(),
            "scripts"      => [],
            "state"        => [],
            "title"        => $this->title,
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
