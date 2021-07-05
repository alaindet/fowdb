<?php

namespace App\Core\Services\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    /** @var \PDO */
    public $pdo;

    public function __construct(array $config)
    {
        $dns = (
            "mysql:" .
            "host={$config['host']};" .
            "dbname={$config['database']};" .
            "charset={$config['charset']};" .
            "port={$config['port']}"
        );

        try {
            $this->pdo = new PDO(
                $dns,
                $config['user'],
                $config['password'],
                $config['options'],
            );
        }
        
        catch (PDOException $e) {
            $message = $e->getMessage();
            $code = (int) $e->getCode();
            throw new PDOException($message, $code);
        }
    }
}
