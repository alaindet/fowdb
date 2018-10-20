<?php

namespace App\Views;

use Twig_Loader_Filesystem as TwigLoaderFilesystem;
use Twig_Environment as TwigEnvironment;

class Page
{
    private $twig;
    private $variables = [];

    public function __construct()
    {
        $twigLoader = new TwigLoaderFilesystem(path_views());
        $twigEnvOptions = [ 'cache' => path_cache('views') ];

        $this->twig = new TwigEnvironment($twigLoader, $twigEnvOptions);
    }

    public function setVariables(array $variables = [])
    {
        $this->variables = $variables;
        return $this;
    }

    public function render(string $templatePath = null)
    {
        return $this->twig->render($templatePath, $this->variables);
    }
}
