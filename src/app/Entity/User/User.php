<?php

namespace App\Entity\User;

use App\Base\ORM\Entity\Entity;
use App\Entity\UserRole\UserRole;

class User extends Entity
{
    protected $table = "users";
    protected $foreignKey = "users_id";
    protected $relationships = [
        UserRole::class => "1-n",
    ];

    public $id;
    public $remember_token;
    public $name;
    public $username;
    public $email;
    public $password;
    public $provider;
    public $provider_id;
    public $roles_id;
}
