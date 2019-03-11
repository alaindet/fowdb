<?php

namespace App\Base;

use App\Base\Controller;
use App\Services\Configuration\Configuration;

abstract class ApiController extends Controller
{
    public function __construct()
    {
        Configuration::getInstance()->set('api', true);
    }
}
