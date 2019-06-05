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
 * - public $templateName;
 * - protected function process(): void;
 * - Input names
 * - Template vars
 */
abstract class AbstractComponent implements ComponentInterface
{
    protected $input;
    public $templateName;
    protected $templateVars;
    protected $templatePath;

    public function __construct(object $input = null)
    {
        $this->setInput($input);

        $relativePath = "components/{$this->templateName}.tpl.php";
        $this->templatePath = Paths::inTemplatesDir($relativePath);
    }

    /**
     * If $input is NULL, reset $this->input
     * If $input is a callback, execute it and pass previous input
     * If $input is object, store it
     *
     * @param object|callable $input
     * @return ComponentInterface
     */
    public function setInput($input = null): ComponentInterface
    {
        if ($input === null) {
            $this->input = new \stdClass();
            return $this;
        }

        if (is_callable($input)) {
            $this->input = $input($this->input);
            return $this;
        }

        $this->input = $input;
        return $this;
    }

    public function getInput(): ?object
    {
        return $this->state;
    }

    /**
     * If no $vars are passed (NULL), reset $this->templateVars
     *
     * @param object $vars
     * @return ComponentInterface
     */
    public function resetTemplateVars(): ComponentInterface
    {
        $this->templateVars = new \stdClass();
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
        // Reset or initialize template variables
        $this->templateVars = new \stdClass();

        // Transform input into template variables
        $this->process();

        // Render template file
        return ComponentManager::renderPhpTemplate(
            $this->templatePath,
            $this->templateVars
        );
    }
}
