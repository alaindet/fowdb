<?php

namespace App\Views\Component;

use App\Views\Component\Interfaces\ComponentInterface;
use App\Views\Component\ComponentManager;
use App\Utils\Paths;

/**
 * This is the base class for reusable components
 * 
 * Every non-trivial component has a class that
 * - Processes and filters component's input
 * - Sets some shared state object between class and template
 * - Renders the template as a string and returns it
 * 
 * Define these in concrete class
 * - public $filename;
 * - protected function process(): void;
 * - Input names
 * - Template vars
 */
abstract class AbstractComponent implements ComponentInterface
{
    protected $state;
    protected $templateVars;
    protected $templatePath;

    public function __construct(object $state = null)
    {
        $relativePath = "components/{$this->filename}.tpl.php";
        $this->templatePath = Paths::inTemplatesDir($relativePath);
        $this->templateVars = new \stdClass();
        $this->state = ($state !== null) ? $state : new \stdClass();
    }

    public function setState(object $state): ComponentInterface
    {
        $this->state = $state;
        return $this;
    }

    public function getState(): ?object
    {
        return $this->state;
    }

    public function setTemplateVars(object $vars): ComponentInterface
    {
        $this->templateVars = $vars;
        return $this;
    }

    public function getTemplateVars(): ?object
    {
        return $this->templateState;
    }

    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    protected function process(): void
    {
        //
    }

    /**
     * Renders the template and returns the output
     * This is called by the component's render() method usually
     * 
     * No $this->templateState props can start with "app*" (ex.: appPath)
     * Inside the template, $this is bound to $this->templateState
     * 
     * @param object $fd_vars
     * @return string
     */
    public function render(): string
    {
        $this->process();

        return ComponentManager::renderPhpTemplate(
            $this->templatePath,
            $this->templateVars
        );
    }
}
