<?php

namespace App\Services\Database;

use App\Base\Singleton;
use App\Exceptions\DatabaseException;
use App\Services\Config;
use App\Services\Database\Pageable;
use App\Services\Database\Statement\DeleteSqlStatement;
use App\Services\Database\Statement\InsertSqlStatement;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Services\Database\Statement\SqlStatement;
use App\Services\Database\Statement\UpdateSqlStatement;
use PDO;
use PDOStatement;

class Database
{
    use Singleton;
    use Pageable;

    /**
     * PDO object
     *
     * @var PDO
     */
    private $pdo;

    /**
     * PDOStatement object
     *
     * @var \PDOStatement
     */
    private $query;

    /**
     * SqlStatement object
     *
     * @var SqlStatement
     */
    private $statement;

    /**
     * Map of bound values
     *
     * @var array
     */
    private $boundValues;

    /**
     * Instantiates and configures the database connection
     * 
     * - Reads configuration variables
     * - Instantiates a PDO instance
     * - Sets attribute: throw exceptions on errors
     * - Sets attribute: return associative arrays (column => value) by default
     */
    protected function __construct()
    {
        // Read the configuration variables
        $cfg = Config::getInstance();
        $host = $cfg->get('db.host');
        $name = $cfg->get('db.name');
        $user = $cfg->get('db.user');
        $password = $cfg->get('db.password');

        // Create the connection
        $this->pdo = new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8",
            $user,
            $password
        );

        // Any error throws an exception
        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        // Fetch results as array of associative arrays column => value
        $this->pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
    }

    /**
     * Sets the sql statement
     *
     * @param SqlStatement $statement
     * @return Database
     */
    private function statement(SqlStatement $statement): Database
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Stores an INSERT statement
     *
     * @param InsertSqlStatement $statement
     * @return Database $this
     */
    public function insert(InsertSqlStatement $statement): Database
    {
        return $this->statement($statement);
    }

    /**
     * Alias for Database::insert
     *
     * @param InsertSqlStatement $statement
     * @return Database $this
     */
    public function create(InsertSqlStatement $statement): Database
    {
        return $this->insert($statement);
    }

    /**
     * Stores a SELECT statement
     *
     * @param SelectSqlStatement $statement
     * @return Database $this
     */
    public function select(SelectSqlStatement $statement): Database
    {
        return $this->statement($statement);
    }

    /**
     * Alias for Database::insert()
     *
     * @param SelectSqlStatement $statement
     * @return Database $this
     */
    public function read(SelectSqlStatement $statement): Database
    {
        return $this->select($statement);
    }

    /**
     * Stores an UPDATE statement
     *
     * @param UpdateSqlStatement $statement
     * @return Database $this
     */
    public function update(UpdateSqlStatement $statement): Database
    {
        return $this->statement($statement);
    }

    /**
     * Stores a DELETE statement
     *
     * @param DeleteSqlStatement $statement
     * @return Database $this
     */
    public function delete(DeleteSqlStatement $statement): Database
    {
        return $this->statement($statement);
    }

    /**
     * Stores values to be bound to the PDO statement later on
     *
     * @param array $values
     * @return Database $this
     */
    public function bind(array $values): Database
    {
        $this->boundValues = $values;

        return $this;
    }

    /**
     * Helps to bind values with proper PDO::PARAM_* constant
     *
     * @param mixed $value Value to be tested
     * @return int PDO::PARAM_* proper value
     */
    private function getParameterFlag($value): int
    {
        $type = gettype($value);
        if ($type === 'string')      return PDO::PARAM_STR;
        elseif ($type === 'integer') return PDO::PARAM_INT;
        elseif ($type === 'boolean') return PDO::PARAM_BOOL;
        elseif ($type === 'NULL')    return PDO::PARAM_NULL;
    }

    /**
     * Binds passed values to placeholders in the passed prepared statement
     * 
     * CAUTION: $values MUST be an associative array placeholder => value
     * and placeholder MUST match the parameter, so it MUST be prefixed with :
     * 
     * Ex.:
     * Bad  => [':name' => 'Alain']
     * Good => ['name' => 'Alain']
     *
     * @param PDOStatement $query Reference to prepared statement
     * @param array $values Reference to values to bind
     * @return void
     */
    private function bindValues(
        PDOStatement &$query,
        array &$values = null
    ): void
    {
        if (!empty($values)) {
            foreach ($values as $placeholder => $value) {
                $query->bindValue(
                    $placeholder,
                    $value,
                    $this->getParameterFlag($value)
                );
            }
        }
    }

    /**
     * Executes the prepared statement
     * This should be called with INSER, UPDATE and DELETE statements
     *
     * @return Database $this
     */
    public function execute(): Database
    {
        // Prepare
        $this->query = $this->pdo->prepare($this->statement->toString());

        // Bind passed values to the prepared statement
        $this->bindValues($this->query, $this->boundValues);

        // Execute
        $executed = $this->query->execute();

        // ERROR: Couldn't execute the query
        if (!$executed) {
            throw new DatabaseException("Couldn't execute the query");
        }

        return $this;
    }

    /**
     * Returns results from database as array of results
     * 
     * By default ($className = null), each element is an assoc array col => val
     * If given a $className, each element is an instance of that class
     * 
     * Fetch style and argument are further discussed here
     * http://php.net/manual/en/pdostatement.fetch.php
     * http://php.net/manual/en/pdostatement.fetchall.php
     * 
     * @param string $className (Optional) Each element is instance of the class
     * @return array Array of results, elements type may vary
     */
    public function get(string $className = null): array
    {
        $this->execute();

        // Each result is an instance of given class
        if (isset($className)) {

            return $this->query->fetchAll(PDO::FETCH_CLASS, $className);

        }
        
        // Each result is an associative array like column => value
        else {

            return $this->query->fetchAll();

        }
    }

    /**
     * Return first result only. Accepts a class name (see Database::get)
     * 
     * @param string $className (Optional) Each element is instance of the class
     * @return mixed Associate array (default) or object (if $className != null)
     */
    public function first(string $className = null)
    {
        $results = $this->get($className);

        if (!isset($results[0])) return [];
        
        return $results[0];
    }

    /**
     * Uses existing FROM and WHERE clauses on SelectSqlStatament
     * Works only when a SelectSqlStatement is set
     *
     * @param string $field Custom field to count on, default is 'id'
     * @return integer
     */
    public function count(string $field = null): int
    {
        // ERROR: Missing statement
        if (!isset($this->statement)) {
            throw new DatabaseException('Missing statement to copy');
        }

        // Set default value on field, if none given
        $field = isset($field) ? "`{$field}`" : '*';

        // Copy the current SELECT statement
        $statement = clone $this->statement;

        // Re-set SELECT, LIMIT and OFFSET clauses
        $statement
            ->resetSelect()
            ->select("COUNT({$field}) as `count`")
            ->limit(1000000) // Reasonable upper limit: 1 million
            ->offset(0);

        // Prepare the statement
        $query = $this->pdo->prepare($statement->toString());

        // Bind passed values to the prepared statement
        $this->bindValues($query, $this->boundValues);

        // Execute
        $query->execute();

        // Fetch
        $results = $query->fetchAll();

        // Return the row count as integer
        return (int) $results[0]['count'];
    }

    /**
     * Resets the auto-increment counter of a given table
     *
     * @param string $table
     * @return Database
     */
    public function resetAutoIncrement(string $table): Database
    {
        $this->pdo->exec("ALTER TABLE `{$table}` AUTO_INCREMENT = 1");

        return $this;
    }

    /**
     * Executes a raw non-SELECT statement
     *
     * @param string $statement
     * @return integer Number of affected rows
     */
    public function rawStatement(string $statement): int
    {
        return $this->pdo->exec($statement);
    }

    /**
     * Executes a raw SELECT statement, returns associative array of results
     * or [] if no results. 
     *
     * @param string $sql
     * @return array
     */
    public function rawSelect(string $sql): array
    {
        $statement = $this->pdo->query($sql);

        if (!$statement) return [];

        $results = $statement->fetchAll();
        $statement->closeCursor();

        return $results;
    }

    /**
     * Performs a raw query to get rows count back
     *
     * @param string $table The table name
     * @param string $condition The raw WHERE condition
     * @param string $field The field to count on, default is 'id'
     * @return integer The rows count
     */
    public function rawCount(
        string $table,
        string $condition,
        string $field = 'id'
    ): int
    {
        $sqlString = "SELECT COUNT(`{$field}`) as `count` "
                   . "FROM {$table} "
                   . "WHERE {$condition}";

        $raw = $this->rawSelect($sqlString);

        return (int) $raw[0]['count'];
    }
}
