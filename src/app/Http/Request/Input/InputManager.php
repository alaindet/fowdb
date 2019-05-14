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

    public function all(): InputObject
    {
        return $this->input;
    }

    public function get(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->input->get;
        }

        return $this->input->get->{$key} ?? $default;
    }

    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->input->post;
        }

        return $this->input->post->{$key} ?? $default;
    }

    public function files(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->input->files;
        }

        return $this->input->files->{$key} ?? $default;
    }

    public function has(string $key, string $method = null): bool
    {
        if ($method === null) {
            foreach ($this->input as $data) {
                if (isset($data->{$key})) {
                    return true;
                }
            }
            return false;
        }

        return isset($this->input->{$method}->{$key});
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
}
