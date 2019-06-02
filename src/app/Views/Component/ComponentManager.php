<?php

namespace App\Views\Component;

use App\Views\Component\Components\Form;
use App\Views\Component\Components\Navigation;
use App\Views\Component\Exceptions\ComponentNotFoundException;
use App\Utils\Paths;

abstract class ComponentManager
{
    public const NO_CLASS = 0;
    static protected $instances = [];

    static protected $components = [

        // TEST
        // "test/with-class" => \App\Views\Component\Components\Test\WithClassComponent::class,
        // "test/without-class" => self::NO_CLASS,
        // "test/multiple" => \App\Views\Component\Components\Test\MultipleComponent::class,
        "auth/logout"                 => self::NO_CLASS,
        "form/button-checkbox"        => self::NO_CLASS,
        "navigation/breadcrumb"       => Navigation\Breadcrumb::class,

        "form/button-checkboxes"      => self::NO_CLASS,
        "form/button-dropdown"        => self::NO_CLASS,
        "form/button-radio"           => self::NO_CLASS,
        "form/input-clear"            => self::NO_CLASS,
        "form/input-dropdown"         => Form\InputDropdown::class,
        "form/select-multiple-handle" => self::NO_CLASS,
        "form/select-multiple-items"  => self::NO_CLASS,
        "form/select-submit"          => self::NO_CLASS,
        "navigation/pagination"       => Navigation\Pagination::class,
        "navigation/top-anchor"       => self::NO_CLASS,
    ];

    /**
     * Renders a component by its name with the provided input object
     *
     * @param string $name
     * @param ?object $input Optional
     * @return string HTML output of the component
     */
    static public function renderComponent(
        string $name,
        object $input = null
    ): string
    {
        // ERROR: No component with given name
        if (!isset(self::$components[$name])) {
            throw new ComponentNotFoundException($name);
        }

        $componentClass = self::$components[$name];

        // No class, just render the template file
        if ($componentClass === self::NO_CLASS) {
            $path = Paths::inTemplatesDir("components/{$name}.tpl.php");
            return self::renderPhpTemplate($path, $input);
        }

        // Fetch previous instance of this component or store a new one
        if (isset(self::$instances[$componentClass])) {
            $component = self::$instances[$componentClass];
        } else {
            $component = new $componentClass;
            self::$instances[$componentClass] = $component;
        }
        
        $component->setInput($input);
        return $component->render();
    }

    /**
     * Renders a .php template file and returns output as a string (HTML)
     * Binds the $input as $this into the template
     * $templatePath is available inside the template's full path
     * 
     * Ex.:
     * $input = (object) [ "foo" => 123 ];
     * Inside template
     * <?php echo $this->foo; ?>
     *
     * @param string $absolutePath
     * @param ?object $input Optional
     * @return string
     */
    static public function renderPhpTemplate(
        string $absolutePath,
        object $input = null
    ): string
    {
        return (
            (
                function ($templatePath) {
                    ob_start();
                    include $templatePath;
                    return ob_get_clean();
                }
            )->bindTo($input)
        )($absolutePath);
    }
}
