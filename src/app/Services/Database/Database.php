<?php

namespace App\Services\Database;

use App\Base\Items\ItemsCollection;
use App\Base\Singleton;
use App\Exceptions\DatabaseException;
use App\Services\Database\Exceptions\ExecutionException;
use App\Services\Database\Exceptions\MissingStatementException;
use App\Services\Configuration\Configuration;
use App\Services\Database\Interfaces\DatabaseInterface;
use App\Services\Database\Statement\DeleteSqlStatement;
use App\Services\Database\Statement\InsertSqlStatement;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Services\Database\Statement\SqlStatement;
use App\Services\Database\Statement\UpdateSqlStatement;
use PDO;
use PDOStatement;

class Database implements DatabaseInterface
{
    use Singleton;

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
    private $boundValues = [];

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
        $config = Configuration::getInstance();
        $host = $config->get('db.host');
        $name = $config->get('db.name');
        $user = $config->get('db.user');
        $password = $config->get('db.password');

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
    public function insert(InsertSqlStatement $statement): DatabaseInterface
    {
        return $this->statement($statement);
    }

    /**
     * Alias for Database::insert
     *
     * @param InsertSqlStatement $statement
     * @return Database $this
     */
    public function create(InsertSqlStatement $statement): DatabaseInterface
    {
        return $this->insert($statement);
    }

    /**
     * Stores a SELECT statement
     *
     * @param SelectSqlStatement $statement
     * @return Database $this
     */
    public function select(SelectSqlStatement $statement): DatabaseInterface
    {
        return $this->statement($statement);
    }

    /**
     * Alias for Database::insert()
     *
     * @param SelectSqlStatement $statement
     * @return Database $this
     */
    public function read(SelectSqlStatement $statement): DatabaseInterface
    {
        return $this->select($statement);
    }

    /**
     * Stores an UPDATE statement
     *
     * @param UpdateSqlStatement $statement
     * @return Database $this
     */
    public function update(UpdateSqlStatement $statement): DatabaseInterface
    {
        return $this->statement($statement);
    }

    /**
     * Stores a DELETE statement
     *
     * @param DeleteSqlStatement $statement
     * @return Database $this
     */
    public function delete(DeleteSqlStatement $statement): DatabaseInterface
    {
        return $this->statement($statement);
    }

    /**
     * Stores values to be bound to the PDO statement later on
     *
     * @param array $values
     * @return Database $this
     */
    public function bind(array $values): DatabaseInterface
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
        return [
            "string" => PDO::PARAM_STR,
            "integer" => PDO::PARAM_INT,
            "boolean" => PDO::PARAM_BOOL,
            "NULL" => PDO::PARAM_NULL
        ][gettype($value)];
    }

    /**
     * Binds values to placeholders in the given prepared statement
     * 
     * Bound values MUST be an associative array like placeholder => value
     * and placeholder MUST match the parameter, so it MUST be prefixed with :
     * 
     * Ex.:
     * Bad  => ['name' => 'Alain']
     * Good => [':name' => 'Alain']
     *
     * @param PDOStatement $query Prepared statement
     * @param array $values
     * @return PDOStatement Same query with bounded values
     */
    private function bindValues(PDOStatement $query): PDOStatement
    {
        foreach ($this->boundValues as $placeholder => $value) {
            $flag = $this->getParameterFlag($value);
            $query->bindValue($placeholder, $value, $flag);
        }

        return $query;
    }

    /**
     * Executes the prepared statement
     * This should be called with INSER, UPDATE and DELETE statements
     *
     * @return Database $this
     */
    public function execute(): DatabaseInterface
    {
        // ERROR: Missing statement
        if ($this->statement === null) {
            throw new MissingStatementException();
        }

        $sql = $this->statement->toString();
        $this->query = $this->pdo->prepare($sql);
        $this->query = $this->bindValues($this->query);
        $executed = $this->query->execute();

        // ERROR: Couldn't execute the query
        if (!$executed) {
            throw new ExecutionException();
        }

        return $this;
    }

    /**
     * Returns results from database as array or ItemsCollection instance
     * 
     * By default ($className = null), each element is an assoc array col => val
     * If given a $className, each element is an instance of that class
     * And a collection is returned instead
     * 
     * Fetch style and argument are further discussed here
     * http://php.net/manual/en/pdostatement.fetch.php
     * http://php.net/manual/en/pdostatement.fetchall.php
     * 
     * @param string $className (Optional) Each element is instance of the class
     * @return array|ItemsCollection Array or ItemsCollection of results
     */
    public function get(string $className = null)
    {
        $this->execute();

        // Each result is an instance of given class
        if (isset($className)) {
            $results =  $this->query->fetchAll(PDO::FETCH_CLASS, $className);
            return (new ItemsCollection)->set($results);
        }
        
        // Each result is an associative array like column => value
        return $this->query->fetchAll();
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

        if (isset($className)) {
            return $results->first();
        }

        return $results[0] ?? null;
    }

    /**
     * Returns row count using existing SelectSqlStatement and bound values
     * 
     * Cloning objects is fine *ONLY IF* props are not objects themselves
     * https://dcsg.me/articles/dont-clone-your-php-objects-deepcopy-them/
     *
     * @param string $field Custom field to count on, default is 'id'
     * @return integer Row count
     */
    public function count(string $field = null): int
    {
        // ERROR: Missing statement
        if ($this->statement === null) {
            throw new MissingStatementException();
        }

        $field = ($field !== null) ? "`{$field}`" : '*';

        $sql = (clone $this->statement)
            ->resetSelect()
            ->resetLimit()
            ->resetOffset()
            ->select("COUNT({$field}) as `count`")
            ->toString();

        $query = $this->pdo->prepare($sql);
        $query = $this->bindValues($query);
        $query->execute();
        $results = $query->fetchAll();

        return (int) $results[0]["count"];
    }

    /**
     * Resets the auto-increment counter of a given table
     *
     * @param string $table
     * @return Database
     */
    public function resetAutoIncrement(string $table): DatabaseInterface
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
        string $field = "id"
    ): int
    {
        $sqlString = (
            "SELECT COUNT(`{$field}`) as `count` ".
            "FROM {$table} ".
            "WHERE {$condition}"
        );

        $raw = $this->rawSelect($sqlString);

        return (int) $raw[0]["count"];
    }
}
