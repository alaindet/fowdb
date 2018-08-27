<?php

namespace App;

use PDO;

class Database
{
    private static $instance = null; // Instance of this class
    private $pdo; // PDO object container
    private $query; // Last executed query on db PDOStatement object

    /**
     * Creates database connection (!!private constructor!!)
     */
    private function __construct()
    {
        // Create PDO connection using pre-defined constants
        $this->pdo = new PDO(
            'mysql:host='.APP_DB_HOST.';dbname='.APP_DB_NAME.';charset=utf8',
            APP_DB_USER,
            APP_DB_PASSWORD
        );

        // Db config: Throw exceptions on errors, fetch assoc array as default
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Gets database connection (as Singleton)
     *
     * @param boolean $returnPDO PDO object returned instead of wrapper
     * @return object Db connection wrapper or PDO object directly if requested
     */
    public static function getInstance($returnPDO = false)
    {
        if (null === self::$instance) {
            $className = __CLASS__;
            self::$instance = new $className();
        }

        return $returnPDO ? self::$instance->pdo : self::$instance;
    }

    /**
     * Executes raw SQL statement on database
     *
     * @param string $sql Statement to be executed
     * @return boolean
     */
    public function execute($sql)
    {
        return $this->pdo->exec($sql);
    }

    /**
     * Reads the database and gets results
     * If no $values, directly execute $sql
     * If $values is set, it is values to be bind and $sql *MUST* have :placeholders
     *
     * @param string $sql if no $vluaes, execute it directly
     * @param array $values if set, is values to be bind, *ONLY* use when $sql has ? placeholders
     * @param bool $first whether to return just first row or not
     * @return mixed array of values from db if success, FALSE on failure
     */
    public function get($sql = null, $values = null, $first = false)
    {
        // ERROR: Invalid input
        if (!isset($sql) OR !is_string($sql)) {
            return false;
        }

        // No values to bind
        if (!isset($values)) {

            // Create PDOStatement object
            $this->query = $this->pdo->query($sql);
        }

        // Values to bind!
        else {

            // Prepare SQL statement
            $this->query = $this->pdo->prepare($sql);

            // Bind values
            // http://php.net/manual/en/pdostatement.bindvalue.php#80285
            foreach ($values as $placeholder => &$value) {
                $this->query->bindValue(
                    $placeholder,
                    $value,
                    $this->getParameterFlag($value)
                );
            }

            // ERROR: Can't execute!
            if (!$this->query->execute()) {
                return false;
            }
        }

        // Fetch results from the database
        $this->results = $this->query->fetchAll();

        // ERROR: No results!
        if (empty($this->results)) {
            return false;
        }

        // Return results
        return $first ? $this->results[0] : $this->results;

        // Return results
        return $first ? $this->results[0] : $this->results;
    }

    /**
     * Returns # of rows a query would return on given table (used in pagination)
     *
     * @param string $table to be queried
     * @param string $filter WHERE clause (optional, default is return all)
     * @param mixed any value to be bind
     * @return int Count of rows from db
     */
    public function getCount($table, $filter = "TRUE", $values = null)
    {
        $query = "SELECT COUNT(id) as count FROM {$table} WHERE {$filter}";
        $data = $this->get($query, $values);
        return (int) $data[0]['count'];
    }

    /**
     * Inserts values into the database
     *
     * @param string $table
     * @param array $values Assoc [field1 => value, field2 => value, ...]
     * @param bool $updateOnDuplicate If TRUE, update entry on duplicate, default is FALSE
     * @return mixed Last row's ID as int or FALSE on error
     */
    public function insert($table, $values = null, $updateOnDuplicate = false)
    {
        // ERROR: Invalid input
        if (!is_string($table) || !is_array($values)) {
            return false;
        }

        // Get fields' names from $values input
        $fields = array_keys($values);

        // Assemble sql statement (put placeholders like :field)
        $_fields = implode(", ", $fields);
        $placeholders = ":" . implode(", :", $fields);
        $sql = "INSERT INTO {$table} ({$_fields}) VALUES ({$placeholders})";

        // Add SQL to update entry on duplicate, if user requested
        if ($updateOnDuplicate) {
            $updateSql = [];
            foreach ($fields as &$field) {
                $updateSql[] = "{$field}=VALUES({$field})";
            }
            $sql .= " ON DUPLICATE KEY UPDATE " . implode(", ", $updateSql);
        }

        // ERROR: Could not prepare the statement
        if (!$this->query = $this->pdo->prepare($sql)) {
            return false;
        }

        // Bind values (http://php.net/manual/en/pdostatement.bindvalue.php#80285)
        foreach($values as $field => &$value) {
            $placeholder = ":".$field;
            $this->query->bindValue(
                $placeholder,
                $value,
                $this->getParameterFlag($value)
            );
        }

        return $this->query->execute() ? (int) $this->pdo->lastInsertId() : false;
    }

    /**
     * Deletes rows from tables
     *
     * @param string $table
     * @param string $condition of WHERE
     * @param array $values to bind. Ex.: [":id"=>123] **MIND THE : COLUMN!**
     * @return mixed # of deleted rows as int or false on error
     */
    public function delete($table, $condition = null, $values = null)
    {
        // If no values to bind, execute it immediately
        if (!isset($values)) {
            // Using PDO::exec instead of PDOStatement::execute() because no need for results
            return $this->pdo->exec("DELETE FROM `{$table}` WHERE {$condition}");
        }

        // Prepare SQL statement (values set)
        $this->query = $this->pdo->prepare("DELETE FROM `{$table}` WHERE {$condition}");

        // Bind values (http://php.net/manual/en/pdostatement.bindvalue.php#80285)
        foreach($values as $parameter => &$value) {
            $this->query->bindValue(
                $parameter,
                $value,
                $this->getParameterFlag($value)
            );
        }

        return $this->query->execute();
    }


    /**
     * Updates rows from tables
     *
     * Database::update(
     *    "cards",
     *    ["atk"=>9999],
     *    "atk>:atk",
     *    [":atk" => 1000]
     * );
     *
     * @param string $table
     * @param array $values assoc array of field=>newvalue pairs
     * @param string $condition of WHERE
     * @param array $conditionValues assoc array of values to be bind to conditions
     * @return mixed # of updated rows as int or false on error
     */
    public function update(
        $table,
        $values = null,
        $condition = null,
        $conditionValues = null
    )
    {
        // ERROR: Invalid input
        if (!is_string($table) OR !is_array($values)) {
            return false;
        }

        // Check if condition is passed (Apply to none on default)
        $condition = isset($condition) ? $condition : "FALSE";

        // Generate placeholders for SET values
        $temp = [];
        foreach ($values as $field => &$value) {
            $temp[] = "{$field} = :{$field}";
        }
        $placeholders = implode(", ", $temp);

        // Build SQL
        $sql = "UPDATE {$table} SET {$placeholders} WHERE {$condition}";

        // Prepare SQL statement
        $this->query = $this->pdo->prepare($sql);

        // Bind values into SET clause (http://php.net/manual/en/pdostatement.bindvalue.php#80285)
        foreach($values as $parameter => &$value) {
            $placeholder = ":".$parameter;
            $this->query->bindValue(
                $placeholder,
                $value,
                $this->getParameterFlag($value)
            );
        }

        // Check if condition needs binding
        if (isset($conditionValues)) {
            foreach ($conditionValues as $parameter => &$value) {
                $this->query->bindValue(
                    $parameter,
                    $value,
                    $this->getParameterFlag($value)
                );
            }
        }

        return $this->query->execute();
    }

    /**
     * Resets ID field of a table
     * 
     * @param string $table - To be reset
     * @return boolean
     */
    public function resetAutoIncrement($table)
    {
        return $this->pdo->exec("ALTER TABLE `{$table}` AUTO_INCREMENT = 1");
    }

    /**
     * Helps to bind values with proper PDO::PARAM_* const
     *
     * @param any $x variable to be tested
     * @return int PDO::PARAM_* proper value
     */
    private function getParameterFlag($x)
    {
        switch(gettype($x)) {
            case 'boolean': return \PDO::PARAM_BOOL; // Boolean
            case 'integer': return \PDO::PARAM_INT; // Integer
            case 'string': return \PDO::PARAM_STR; // String
            case 'NULL': return \PDO::PARAM_NULL; // NULL
        }   
    }

    /**
     * Return underlying PDO object directly
     *
     * @return obj PDO object
     */
    public function getPDO()
    {
        return $this->pdo;
    }
}
