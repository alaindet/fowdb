<?php

namespace App\Core\Http;

use PDO;

use App\Core\Services\Database\DatabaseConfiguration;
use App\Core\Services\Database\DatabaseConnection;
use App\Core\Services\Configuration\Configuration;

abstract class Controller
{
    /** @var \App\Core\Services\Configuration\Configuration */
    protected $config;

    /** @var \App\Core\Services\Database\DatabaseConnection */
    protected $db;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
        $this->db = new DatabaseConnection($config->get('database'));
    }

    public function render($content): string {
        return json_encode($content);
    }
}
