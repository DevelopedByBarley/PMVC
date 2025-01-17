<?php

namespace Core;

use Illuminate\Support\Collection;

/* 
    What i want ?

    $request()->validators([
      "name" => ['string', 'required', 'min:5', 'max:5', 'uniq:email:users']
    ])
  */

class Validator
{
  protected $ret = [];

  private static function structure($rules)
  {
    $ret = [];
    foreach ($rules as $key => $rule) {
      $ret[$key] = $rule;
    }

    return $ret;
  }


  public static function validate($request, $rules)
  {

    $ret = [];
    $rules = static::structure($rules);

    foreach ($request as $req_key => $req_value) {
      $validator = $rules[$req_key] ?? [];
      foreach ($validator as  $val_value) {
        if (strpos($val_value, ':')) {
          $parts = explode(":", $val_value);

          $validatorName = $parts[0];
          $validatorValue = $parts[1];

          $ret[$req_key][$validatorName] = [
            "status" => static::$validatorName($req_value, $validatorValue),
            'errorMessage' => !static::$validatorName($req_value, $validatorValue) ? static::errorMessages($validatorName, $validatorValue) : ''
          ];
        } else {
          $ret[$req_key][$val_value] = [
            "status" => static::$val_value($req_value),
            'errorMessage' => !static::$val_value($req_value) ? static::errorMessages($val_value) : ''
          ];
        }
      }
    }


    $errors = static::errors($ret);
    if (!empty($errors)) return ValidationException::throw($errors, $request);

    return $request;
  }
  
  public function throw($errors) {

  }

  public static function errors($ret)
  {
    $errors = [];
    foreach ($ret as $req_key => $validators) {
      foreach ($validators as $validator) {
        if (!$validator['status']) {
          $errors[$req_key]['errors'][] = $validator['errorMessage'];
        }
      }
    }

    return $errors;
  }

  protected static function required($value)
  {
    if (!$value || $value === '') return true;
    return true;
  }
  protected static function string($value)
  {
    return (bool)is_string($value);
  }
  protected static function min($value, $length)
  {
    return (int)strlen($value) >= $length;
  }
  protected static function max($value, $length)
  {
    return (int)strlen($value) <= $length;
  }

  protected static function password($value) {}

  protected static function unique($req_value, $val_value)
  {
    return true;
  }


  private static function errorMessages($validator, $param = '')
  {
    $lang = "hu";
    $messages = [
      'required' => [
        'hu' => 'Kitöltés kötelező!',
        'en' => 'This field is required!',
      ],
      'string' => [
        'hu' => "A mező csak szöveg lehet!",
        'en' => "The field must be at least {$param} characters long.",
      ],
      'min' => [
        'hu' => "A mező nem lehet rövidebb, mint {$param} karakter.",
        'en' => "The field cannot be shorter than {$param} characters.",
      ],
      'max' => [
        'hu' => "A mező nem lehet hosszabb, mint {$param} karakter.",
        'en' => "The field cannot be longer than {$param} characters.",
      ],
    ];

    return $messages[$validator][$lang];
  }
}
