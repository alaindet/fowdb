<?php

namespace App\Services\OpenGraphProtocol;

use App\Base\Singleton;

/**
 * Full specification here http://ogp.me/
 */
class OpenGraphProtocol
{
    use Singleton;

    public $htmlPrefix = 'og: http://ogp.me/ns#';
    private $props = [];

    public function toHtml(): string
    {
        $html = '';
        foreach ($this->props as $property => $content) {
            $html .= "<meta property=\"{$property}\" content=\"{$content}\" />";
        }
        return $html;
    }

    public function getHtmlPrefix(): string
    {
        return $this->htmlPrefix;
    }

    /**
     * Can be read also
     *
     * @param string $value
     * @return any Can return this instance or the title prop value
     */
    public function title(string $value = null)
    {
        if (isset($value)) {
            $this->props['og:title'] = $value;
            return $this;
        }

        return $this->props['og:title'];
    }

    public function url(string $value): OpenGraphProtocol
    {
        $this->props['og:url'] = $value;
        return $this;
    }

    public function type(string $value): OpenGraphProtocol
    {
        $this->props['og:type'] = $value;
        return $this;
    }

    public function image(OpenGraphProtocolImage $image): OpenGraphProtocol
    {
        foreach ($image->getProperties() as $prop => $content) {
            $this->props[$prop] = $content;
        }

        return $this;
    }

    public function description(string $value): OpenGraphProtocol
    {
        $this->props['og:description'] = $value;
        return $this;
    }

    public function locale(string $value): OpenGraphProtocol
    {
        $this->props['og:locale'] = $value;
        return $this;
    }

    public function siteName(string $value): OpenGraphProtocol
    {
        $this->props['og:site_name'] = $value;
        return $this;
    }
}
