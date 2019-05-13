<?php

namespace App\Base\Exceptions;

use App\Base\Exceptions\Alertable;
use App\Base\Exceptions\Previousable;
use App\Http\Request\Input;
use App\Http\Response\JsonResponse;
use App\Http\Response\Redirect;
use App\Services\Alert;
use App\Services\FileSystem\FileSystem;
use App\Services\Session\Session;
use App\Utils\Logger;
use ErrorException;
use Throwable;
use App\Utils\Paths;
use App\Utils\Time;
use App\Services\Configuration\Configuration;

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
        $config = Configuration::getInstance();

        // Bypass any processing, return an API error
        if ($config->get("current.mode") === "api") {
            $response = new JsonResponse;
            $response->setData([
                "error" => 1,
                "message" => $exception->getMessage()
            ]);
            echo $response->render();
            die();
        }

        // Store $_POST data
        if ($exception instanceof Previousable) {
            $input = Input::getInstance();
            Session::set(Input::PREVIOUS_INPUT, $input->post());
            return;
        }

        // Show exception as an alert on the UI
        if ($exception instanceof Alertable) {
            Alert::add($exception->getMessage(), "danger");
            Redirect::to($exception->getRedirectUrl());
            return;
        }

        // Create log data
        $data = [
            "message" => $exception->getMessage(),
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace()
        ];

        // Show readable log of the exception
        if ($config->get("app.env") === "development") {
            echo call_user_func(
                [Logger::class, "html"], // Change to "cli" for CLI debugging
                $data,
                $title = get_class($exception)
            );
            die();
        }

        // The last hope: log the exception into a log file
        $serializedData = serialize($data);
        $time = Time::timestamp("file-nospace");
        $hash = md5($serializedData);
        $filename = Paths::inDataDir("logs/exceptions/{$hash}.txt");
        if (!FileSystem::existsFile($filename)) {
            FileSystem::saveFile($filename, $serializedData);
        }

        $mailToSubject = "FOWDB_EXCEPTION:{$time}_{$hash}";
        $mailTo = "mailto:alain.det@gmail.com?subject={$mailToSubject}";

        echo (
            "An error occurred on our servers, please notify us at ".
            "<a href=\"{$mailTo}\">alain.det@gmail.com</a>. ".
            "Thanks for your help, the FoWDB team."
        );
        die();
    }
}
