<?php

namespace App\Contracts;

interface ModelContract
{
    /**
     * Magic method "get"
     *
     * @return mixed
     */
    public function __get(string $attribute);

    /**
     * Magic method "set"
     *
     * @return mixed
     */
    public function __set(string $attribute, $value);
}
