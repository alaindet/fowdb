<?php

namespace App\Base\Exceptions;

use Exception as BuiltinException;
use App\Base\BaseTrait;

abstract class Exception extends BuiltinException
{
    use BaseTrait;

    public const REDIRECT_HOME = "/";
    public const REDIRECT_BACK = "back";

    /**
     * URI to get redirected to in case the exception implements Alertable
     * 
     * $redirect = 'back' is special: goes to last page, not to URI "back"
     *
     * @var string
     */
    public $redirectTo = self::REDIRECT_HOME;

    public function getRedirectUrl(): string
    {
        return $this->redirectTo;
    }

    public function setRedirectUrl(string $redirectTo): Exception
    {
        $this->redirectTo = $redirectTo;
        return $this;
    }
}
