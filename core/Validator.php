<?php

namespace Core;

use Exception;
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
      $req_value =  sanitize($req_value);
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

  public static function password($value)
  {
    $hasUpperCase = preg_match('/[A-Z]/', $value);
    $hasLowerCase = preg_match('/[a-z]/', $value);
    $hasNumber = preg_match('/\d/', $value);
    $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);
    $isLengthValid = strlen($value) >= 8;

    return $hasUpperCase && $hasLowerCase && $hasNumber && $hasSpecialChar && $isLengthValid;
  }

  public static function comparePw($password, $confirmPassword)
  {
    return $password === $confirmPassword;
  }

  public static function unique($value, $params)
  {
    $paramsArray = explode('|', $params);

    if (count($paramsArray) < 2) {
      throw new Exception("Hibás bemenet: a paraméterek nem megfelelőek.");
    }

    $db = trim($paramsArray[1]); // Táblanév
    $record = trim($paramsArray[0]); // Oszlopnév

    $sql = "SELECT COUNT(*) as count FROM `$db` WHERE `$record` = :value";

    $result = (new Database)->query($sql, ["value" => $value])->get()[0];

    return (int)$result->count === 0;
  }

  public static function phone($value)
  {
    $cleanValue = preg_replace('/[\s\-]/', '', $value);
    $pattern = '/^(?:\+36|06)\d{9}$/';

    return preg_match($pattern, $cleanValue);
  }

  public static function email($value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  public static function noSpaces($value)
  {
    if (strpos($value, ' ') !== false) return false;
    return true;
  }

  public static function numeric($value)
  {
    if (!is_numeric($value)) return false;
    return true;
  }


  public static function hasNum($value)
  {
    return preg_match('/\d/', $value);
  }

  public static function hasUppercase($value)
  {
    return preg_match('/[A-Z]/', $value);
  }

  public static function split($value)
  {
    $words = explode(' ', trim($value));
    return count($words) >= 2 && strlen($words[1]) > 0;
  }






  private static function errorMessages($validator, $param = '')
  {
    $lang = "hu";
    $messages = [
      'required' => [
        'hu' => 'Kitöltés kötelező!',
        'en' => 'This field is required!',
      ],
      'password' => [
        'hu' => "A jelszónak legalább 8 karakter hosszúnak kell lennie, és tartalmaznia kell legalább egy nagybetűt, egy kisbetűt, egy számot és egy speciális karaktert!",
        'en' => "The password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character!",
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
      'email' => [
        'hu' => "Kérjük adjon meg igazi email címet.",
        'en' => "Please enter a valid email address.",
      ],
      'unique' => [
        'hu' => "Ezekkel az adatokkal már nem lehet regisztrálni, kérjük próbálja meg más adatokkal..",
        'en' => "You can not register with that datas, please try again.",
      ]
    ];

    return $messages[$validator][$lang];
  }
}
