<?php

namespace Pagewerx\UswerxApiPhp\Contracts;

class Singleton
{
    protected static self|null $instance = null;

    final private function __construct(){}

    /**
     * @codeCoverageIgnore
     */
    final protected function __clone(){}

    /**
     * @codeCoverageIgnore
     */
    final public function __wakeup(){}

    /**
     * Retrieves an instance of the Singleton Object.
     *
     * If the instance has not been created yet, a new instance of the class will be created.
     *
     * @return static The instance of the class.
     */
    public static function getInstance(): static
    {
        if (static::$instance === null) {
            static::$instance = new static;
        }

        return static::$instance;
    }
}