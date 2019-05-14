<?php

namespace App\Http\Request\Input;

use App\Services\Session\Session;
use App\Base\Singleton;

class InputManager
{
    use Singleton;

    /**
     * The session key of the previous input, if existing
     * 
     * @var string
     */
    public const PREVIOUS = "fd_previous";

    /**
     * The InputObject instance
     *
     * @var InputObject
     */
    private $input = null;

    protected function __construct()
    {
        $this->input = new InputObject($_GET, $_POST, $_FILES);
    }

    public function getInput(): InputObject
    {
        return $this->input;
    }

    public function setPrevious(): self
    {
        Session::set(self::PREVIOUS, $this->input);
        return $this;
    }

    public function getPrevious(): ?InputObject
    {
        return Session::pop(self::PREVIOUS) ?? null;
    }

    public function exists(string $key, string $method = null): bool
    {
        // Look on all methods (GET, POST, FILES)
        if ($method === null) {
            foreach ($this->input as $method) {
                if (isset($this->input->{$method}->{$key})) {
                    return true;
                }
            }
            return false;
        }

        $method = strtolower($method);
        return isset($this->input->{$method}->{$key});
    }

    public function extractInput(array $keys, string $method): object
    {
        $inputData = $this->input->{$method};
        $data = new \stdClass();

        foreach ($keys as $key) {
            $data->{$key} = $inputData->{$key};
        }

        return $data;
    }
}
