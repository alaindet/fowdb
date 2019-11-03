<?php

namespace App\Services\OpenGraphProtocol;

/**
 * Follows http://ogp.me/#structured
 * 
 * All properties are optional, but recommended
 */
class OpenGraphProtocolImage
{
    private $props;

    public function getProperties(): array
    {
        return $this->props;
    }

    public function url(string $value): OpenGraphProtocolImage
    {
        $this->props['og:image'] = $value;
        // $this->props['og:image:url'] = $value; // Equivalent

        return $this;
    }

    public function secureUrl(string $value): OpenGraphProtocolImage
    {
        $this->props['og:image:secure_url'] = $value;
        
        return $this;
    }

    public function mimeType(string $value): OpenGraphProtocolImage
    {
        $this->props['og:image:type'] = $value;
        
        return $this;
    }

    public function width(string $value): OpenGraphProtocolImage
    {
        $this->props['og:image:width'] = $value;
        
        return $this;
    }

    public function height(string $value): OpenGraphProtocolImage
    {
        $this->props['og:image:height'] = $value;
        
        return $this;
    }

    public function alt(string $value): OpenGraphProtocolImage
    {
        $this->props['og:image:alt'] = $value;
        
        return $this;
    }
}
