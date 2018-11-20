<?php

namespace App\Http\Response;

class JsonResponse
{
    private $data;

    public function setData(array $data): JsonResponse
    {
        $this->data = $data;

        return $this;
    }

    public function setCorsHeaders(): JsonResponse
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type');

        return $this;
    }

    private function setJsonHeader(): void
    {
        header('Content-Type: application/json');
    }

    public function render(): string
    {
        $this->setJsonHeader();

        return json_encode(
            $this->data,
            JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES
        );
    }
}
