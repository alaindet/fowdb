<?php

namespace App\Core\Http;

use stdClass;

class Request
{
    /** @var stdClass | null */
    private $body;

    public function computeBody(): void
    {
        $contentType = $_SERVER['CONTENT_TYPE'];

        // No need for form-encoded data
        // if (\strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
        //     $this->body = parse_url(urldecode($input));
        // }
        
        if (\strpos($contentType, 'multipart/form-data') !== false) {
            // TODO
            $this->body = null;
        }

        elseif (\strpos($contentType, 'application/json') !== false) {
            $input = file_get_contents('php://input', 'r');
            \App\Shared\Utils\Utils::dump('input', $input);
            $this->body = json_decode($input);
        }   

        else {
            $this->body = null;
        }
    }

    public function getBody()
    {
        return $this->body;
    }
}
