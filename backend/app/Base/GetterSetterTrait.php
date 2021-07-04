<?php

namespace App\Base;

trait GetterSetterTrait
{
    public function getter(string $name)
    {
        return $this->$name;
    }

    public function setter(string $name, $value)
    {
        $this->$name = $value;
        return $this;
    }

    public function getterSetter(string $name, $value = null)
    {
        if (isset($value)) {
            $this->$name = $value;
            return $this;
        }

        return $this->$name;
    }
}
