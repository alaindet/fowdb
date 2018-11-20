<?php

namespace App\Exceptions;

use Throwable;
use App\Utils\Logger;
use App\Base\Exception;
use App\Exceptions\Alertable;
use App\Exceptions\Jsonable;
use App\Exceptions\Previousable;
use App\Services\Alert;
use App\Http\Response\Redirect;
use App\Services\Config;
use App\Services\Session;
use App\Http\Request\Input;
use App\Http\Response\JsonResponse;

class Handler
{
    public function handler(Throwable $exception): void
    {
        // Store $_POST data
        if ($exception instanceof Previousable) {
            $input = Input::getInstance();
            Session::set(Input::PREVIOUS_INPUT, $input->post());
        }

        // Show exception as an alert
        if ($exception instanceof Alertable) {
            Alert::add($exception->getMessage(), 'danger');
            Redirect::to($exception->getRedirectUrl());
        }

        // Show exception as a JSON
        if ($exception instanceof Jsonable) {
            echo (new JsonResponse)->setData([
                'error' => 1,
                'message' => $exception->getMessage()
            ])->render();
            die();
        }

        $config = Config::getInstance();

        // Show readable log of the exception
        $logger = Logger::class;
        $method = ($config->get('app.env') === 'cli') ? 'cli' : 'html';
        $data = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace()
        ];
        $title = get_class($exception);
        echo call_user_func([$logger, $method], $data, $title);
    }
}
