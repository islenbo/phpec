<?php

namespace PHPEc\Support;

interface Attributeable
{

    /**
     * Determine if an attribute or relation exists on the object.
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name);

    /**
     * Unset an attribute on the object.
     *
     * @param string $name
     * @return void
     */
    public function __unset($name);

    /**
     * Dynamically set attributes on the object.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value);

    /**
     * Dynamically retrieve attributes on the object.
     *
     * @param string $name
     * @return mixed
     */
    public function &__get($name);

}
