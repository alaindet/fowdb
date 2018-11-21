<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Alertable;
use App\Exceptions\ErrorException;
use App\Exceptions\Jsonable;
use App\Exceptions\Previousable;
use App\Http\Request\Input;
use App\Http\Response\JsonResponse;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Services\Session;
use App\Utils\Logger;
use Throwable;

class Handler
{
    /**
     * Global error handler
     * Turns simple errors in manageable exceptions
     *
     * @param integer $level
     * @param string $message
     * @param string $file
     * @param integer $line
     * @return void
     */
    public static function errorHandler(
        int $level,
        string $message,
        string $file,
        int $line
    ): void
    {
        throw new ErrorException($message, $level, $file, $line);
    }

    /**
     * Global exception handler
     *
     * @param Throwable $exception
     * @return void
     */
    public static function handler(Throwable $exception): void
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

        // Show readable log of the exception
        echo call_user_func(
            [
                $class = Logger::class,
                $method = 'html' // Change to 'cli' for CLI debugging
            ],
            $data = [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ],
            $title = get_class($exception)
        );
    }
}
