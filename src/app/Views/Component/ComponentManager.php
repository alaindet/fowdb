<?php

namespace App\Views\Component;

use App\Views\Component\Components\Form;
use App\Views\Component\Components\Navigation;
use App\Views\Component\Exceptions\ComponentNotFoundException;
use App\Utils\Paths;

abstract class ComponentManager
{
    public const NO_CLASS = 0;

    static protected $components = [

        // TEST
        "test/with-class" => \App\Views\Component\Components\Test\WithClassComponent::class,
        "test/without-class" => self::NO_CLASS,

        "auth/logout"                 => self::NO_CLASS,
        "form/button-checkbox"        => self::NO_CLASS,
        "form/button-checkboxes"      => self::NO_CLASS,
        "form/button-dropdown"        => self::NO_CLASS,
        "form/button-radio"           => self::NO_CLASS,
        "form/input-clear"            => self::NO_CLASS,
        "form/input-dropdown"         => Form\InputDropdown::class,
        "form/select-multiple-handle" => self::NO_CLASS,
        "form/select-multiple-items"  => self::NO_CLASS,
        "form/select-submit"          => self::NO_CLASS,
        "navigation/breadcrumb"       => Navigation\Breadcrumb::class,
        "navigation/pagination"       => Navigation\Pagination::class,
        "navigation/top-anchor"       => self::NO_CLASS,
    ];

    static public function renderComponent(
        string $name,
        object $state
    ): string
    {
        $componentClass = self::$components[$name] ?? null;

        if ($componentClass === null) {
            throw new ComponentNotFoundException($name);
        }

        if ($componentClass === self::NO_CLASS) {
            $path = Paths::inTemplatesDir("components/{$name}.tpl.php");
            return self::renderPhpTemplate($path, $state);
        }

        return (new $componentClass($state))->render();
    }

    /**
     * Renders a .php template file and returns output as a string (HTML)
     * Binds the $state input as $this into the template
     * $templatePath is available inside the template's full path
     * 
     * Ex.:
     * $state = (object) [ "foo" => 123 ];
     * Inside template
     * <?php echo $this->foo; ?>
     *
     * @param string $absolutePath
     * @param object $state
     * @return string
     */
    static public function renderPhpTemplate(
        string $absolutePath,
        object $state
    ): string
    {
        return (
            (
                function ($templatePath) {
                    ob_start();
                    include $templatePath;
                    return ob_get_clean();
                }
            )->bindTo($state)
        )($absolutePath);
    }
}
