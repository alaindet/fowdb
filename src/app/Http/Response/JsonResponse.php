<?php

namespace App\Http\Response;

use App\Http\Response\ResponseAbstract;

class JsonResponse extends ResponseAbstract
{
    /**
     * Sets specific headers to allow cross-domain API calls
     *
     * @return ResponseAbstract
     */
    public function setCorsHeaders(): ResponseAbstract
    {
        $this->setHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
            'Access-Control-Max-Age' => '1000',
            'Access-Control-Allow-Headers' => 'Content-Type',
        ]);

        return $this;
    }

    /**
     * Returns the response as JSON
     *
     * @return string
     */
    public function render(): string
    {
        $this->setHeader('Content-Type', 'application/json');

        $this->outputHeaders();

        return json_encode(
            $this->data,
            JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES
        );
    }
}
