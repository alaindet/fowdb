<?php

namespace App\Exceptions;

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
    static public function handler(Throwable $exception): void
    {
        $mode = config("app.mode");

        if ($mode === "web") {
            self::webHandler($exception);
        } elseif ($mode === "cli") {
            self::cliHandler($exception);
        }
    }

    static public function webHandler(Throwable $exception): void
    {
        // Store $_POST data
        if ($exception instanceof Previousable) {
            $input = Input::getInstance();
            Session::set(Input::PREVIOUS_INPUT, $input->post());
        }

        // Show exception as an alert on the UI
        if ($exception instanceof Alertable) {
            Alert::add($exception->getMessage(), 'danger');
            Redirect::to($exception->getRedirectUrl());
        }

        // Show exception as a JSON
        if ($exception instanceof Jsonable) {
            $data = [
                "error" => true,
                "message" => $exception->getMessage()
            ];
            $response = (new JsonResponse)->setData($data);
            echo $response->render();
            die();
        }

        $data = [
            "message" => $exception->getMessage(),
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace()
        ];

        $title = get_class($exception);

        echo Logger::html($data, $title);
    }

    static public function cliHandler(Throwable $exception): void
    {
        $data = [
            "message" => $exception->getMessage(),
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace()
        ];

        $title = get_class($exception);

        echo Logger::cli($data, $title);
    }
}
