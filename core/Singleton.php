<?php

namespace Core;

// Using trait for your can pass in every class with functionality
trait Singleton
{
    // define $instance with default null value;
    protected static $instance = null;

    // Getter function for instance
    public static function getInstance() 
    {
        // check if instance is null
        if (static::$instance === null) {
            static::$instance = new static(); // using static:: to ensure correct class instance is created
        }

        // And return the instance
        return static::$instance;
    }
}
