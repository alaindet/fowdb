<?php

namespace App\Http\Response;

use App\Http\Response\ResponseAbstract;

class PlainTextResponse extends ResponseAbstract
{
    /**
     * Returns a plain text response
     *
     * @return string
     */
    public function render(): void
    {
        $path = $this->data['path'];
        $base = basename($path);
        $size = filesize($path);
        
        // Thanks to
        // https://www.abeautifulsite.net/forcing-file-downloads-in-php
        $this->setHeaders([
            'Content-Disposition' => 'attachment; filename="'.$base.'"',
            'Content-Length' => $size,
            'Content-Type' => 'application/octet-stream;',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => '0',
            'Pragma' => 'private',
        ]);

        $this->outputHeaders();

        readfile($path);
    }
}
