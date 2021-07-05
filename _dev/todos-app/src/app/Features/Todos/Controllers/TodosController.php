<?php

namespace App\Features\Todos\Controllers;

use PDO;

use App\Core\Http\Controller;
use App\Core\Services\Configuration\Configuration;
use App\Core\Services\Database\DatabaseConfiguration;
use App\Core\Services\Database\DatabaseConnection;
use App\Features\Todos\Dtos\CreateTodoDto;
use App\Features\Todos\Dtos\UpdateTodoDto;
use App\Features\Todos\Repositories\TodosRepository;
use App\Shared\Utils\Utils;

class TodosController extends Controller
{
    /** @var \App\Repositories\TodosRepository */
    private $todosRepo;

    public function __construct(Configuration $config)
    {
        parent::__construct($config);
        $this->todosRepo = new TodosRepository($this->db);
    }

    public function create(\stdClass $body): string
    {
        try {
            $dto = new CreateTodoDto();
            $dto->title = $body->title;
            $dto->is_done = $body->is_done;
            $todo = $this->todosRepo->create($dto);
            return $this->render($todo);
        }

        catch (\Exception $e) {
            return $this->render($e->getMessage());
        }
    }

    public function getAll(): string
    {
        try {
            $todos = $this->todosRepo->getAll();
            return $this->render($todos);
        }

        catch (\Exception $e) {
            return $this->render($e->getMessage());
        }
    }

    public function getOne(string $id): string
    {
        try {
            $todo = $this->todosRepo->getOne($id);
            return $this->render($todo);
        }

        catch (\Exception $e) {
            return $this->render($e->getMessage());
        }
    }

    public function update(string $id, \stdClass $body): string
    {
        try {
            $dto = new UpdateTodoDto();
            $dto->id = $id;
            $dto->title = $body->title;
            $dto->is_done = $body->is_done;
            $todo = $this->todosRepo->update($dto);
            return $this->render($todo);
        }

        catch (\Exception $e) {
            return $this->render($e->getMessage());
        }
    }

    public function delete(string $id): string
    {
        try {
            $todo = $this->todosRepo->delete($id);
            return $this->render($todo);
        }

        catch (\Exception $e) {
            return $this->render($e->getMessage());
        }
    }
}
