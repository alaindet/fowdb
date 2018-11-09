<?php

namespace App\Http\Controllers\Admin;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;

/**
 * Contains actions for JUDGE routes only
 * Pubilc actions on cards are provided by ...\CardsController
 */
class CardsController extends Controller
{
    public function indexManage(): string
    {
        return __METHOD__;
    }

    public function createForm(): string
    {
        return __METHOD__;
    }

    public function create(): string
    {
        return __METHOD__;
    }

    public function updateForm(): string
    {
        return __METHOD__;
    }

    public function update(): string
    {
        return __METHOD__;
    }

    public function deleteForm(): string
    {
        return __METHOD__;
    }

    public function delete(): string
    {
        return __METHOD__;
    }
}
