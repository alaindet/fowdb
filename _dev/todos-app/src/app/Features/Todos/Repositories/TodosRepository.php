<?php

namespace App\Features\Todos\Repositories;

use PDO;

use App\Core\Exceptions\NotFoundException;
use App\Core\Exceptions\DuplicateException;
use App\Core\Services\Database\DatabaseConnection;
use App\Features\Todos\Entities\Todo;
use App\Features\Todos\Dtos\CreateTodoDto;
use App\Features\Todos\Dtos\UpdateTodoDto;

class TodosRepository
{
    private string $table = 'todos';

    /** @var \PDO */
    private $pdo;

    public function __construct(DatabaseConnection $conn)
    {
        $this->pdo = $conn->pdo;
    }

    public function create(CreateTodoDto $dto): Todo
    {
        $existing = $this->getAllByTitle($dto->title);

        if (count($existing) > 0) {
            $message = "Todo with title '{$dto->title}' already exists";
            throw new DuplicateException($message);
        }

        $sql = "
            INSERT INTO {$this->table} (title, is_done)
            VALUES (:title, :is_done)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title' => $dto->title,
            ':is_done' => $dto->is_done
        ]);

        $todo = new Todo();
        $todo->id = $this->pdo->lastInsertId();
        $todo->title = $dto->title;
        $todo->is_done = $dto->is_done;

        return $todo;
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, Todo::class);
        $stmt = null;

        return $result;
    }

    public function getOne(string $id): Todo
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, Todo::class);
        $stmt = null;

        if (count($result) === 0) {
            throw new NotFoundException("No todo found with id #{$id}");
        }

        return $result[0];
    }

    public function update(UpdateTodoDto $dto): Todo
    {
        $existing = $this->getAllByTitle($dto->title);

        if (count($existing) > 0) {
            $message = "Todo with title '{$dto->title}' already exists";
            throw new DuplicateException($message);
        }

        $sql = "
            UPDATE {$this->table}
            SET title = :title, is_done = :is_done
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title' => $dto->title,
            ':is_done' => $dto->is_done
        ]);

        $todo = new Todo();
        $todo->id = $dto->id;
        $todo->title = $dto->title;
        $todo->is_done = $dto->is_done;

        return $todo;
    }

    public function getAllByTitle(string $title): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE title = :title";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title
        ]);
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, Todo::class);
        $stmt = null;

        return $result;
    }

    public function delete(string $id): Todo
    {
        $todo = $this->getOne($id);
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        return $todo;
    }
}
