<?php

namespace App\Entity\UserRole;

use App\Base\ORM\Entity\Entity;

class UserRole extends Entity
{
    protected $table = "user_roles";
    protected $foreignKey = "roles_id";

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
