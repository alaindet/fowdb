<?php

namespace App\Core;

use PDO;

use App\Core\Services\Database\DatabaseConfiguration;
use App\Core\Services\Database\DatabaseConnection;
use App\Utils\Utils;

abstract class Controller
{
    public function getDatabaseConnection(): DatabaseConnection
    {
        $dbConfig = require __DIR__ . '/config/database.php';
        $dbConn = new DatabaseConnection($dbConfig);
        return $dbConn;
    }

    public function render($content): string {
        return json_encode($content);
    }
}
