<?php

namespace App\Features\Todos\Dtos;

class CreateTodoDto
{
    public string $title;
    public int $is_done = 0;
}
