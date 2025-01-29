<?php


class DatabaseException extends \Exception
{
  public readonly array $errors;
  public readonly array $old;

  public static function throw($errors, $old)
  {

    $instance = new static('This validation is failded! ');

    $instance->errors = $errors;
    $instance->old = $old;

    throw $instance;
  }
}
