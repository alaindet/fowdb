<?php

namespace App\Features\Users\Dtos;

class AuthenticatedUserDto
{
    public $email;
    public $token;
    public $expireAt;
}
