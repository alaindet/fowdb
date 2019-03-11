<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Alertable;
use ErrorException;
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
     * Please mind some errors are uncatchable no matter what
     * http://php.net/manual/en/class.errorexception.php#95415
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
        throw new ErrorException($message, 0, $level, $file, $line);
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
            return;
        }

        // Show exception as an alert
        if ($exception instanceof Alertable) {
            Alert::add($exception->getMessage(), 'danger');
            Redirect::to($exception->getRedirectUrl());
            return;
        }

        // Show exception as a JSON
        if ($exception instanceof Jsonable) {
            $json = new JsonResponse;
            $json->setData([
                'error' => 1,
                'message' => $exception->getMessage()
            ]);
            echo $json->render();
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
