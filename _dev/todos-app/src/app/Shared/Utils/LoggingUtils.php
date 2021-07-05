<?php

namespace App\Shared\Utils;

/**
 * docker-compose logs -f php-fpm
 */
trait LoggingUtils
{
    static public function log(string $message): void
    {
        $stdout = fopen('php://stdout', 'wb');
        fwrite($stdout, $message . "\n");
        fclose($stdout);
    }

    static public function dump(string $message, $content): void
    {
        $serializedContent = print_r($content, true);
        $logged = "MESSAGE: {$message}\nCONTENT: {$serializedContent}";
        self::log($logged);
    }
}
