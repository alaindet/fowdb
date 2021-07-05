<?php

namespace App\Features\Todos\Dtos;

class UpdateTodoDto
{
    public string $id;
    public string $title;
    public int $is_done;
}
