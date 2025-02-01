<?php

namespace Core;

use Exception;

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
      $req_value = sanitize($req_value);

      if ($req_value === '') {
        $req_value = null;
      }

      $validator = $rules[$req_key] ?? [];
      foreach ($validator as $val_value) {
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
    return !empty($value);
  }
  protected static function string($value)
  {
    return is_string($value);
  }
  protected static function min($value, $length)
  {
    return strlen($value) >= $length;
  }
  protected static function max($value, $length)
  {
    return strlen($value) <= $length;
  }
  protected static function numeric($value)
  {
    return is_numeric($value);
  }
  protected static function date($value)
  {
    return strtotime($value) !== false;
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
    return strpos($value, ' ') === false;
  }
  public static function split($value)
  {
    $words = explode(' ', trim($value));
    return count($words) >= 2 && strlen($words[1]) > 0;
  }

  protected static function nullable($value)
  {

    return $value ?? ($value === null || $value === '');
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
        'en' => "The field must be a string.",
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
        'hu' => "Kérjük, adjon meg valódi email címet.",
        'en' => "Please enter a valid email address.",
      ],
      'unique' => [
        'hu' => "Ezekkel az adatokkal már nem lehet regisztrálni, kérjük próbálja meg más adatokkal.",
        'en' => "You cannot register with this data, please try again.",
      ],
      'password' => [
        'hu' => "A jelszónak tartalmaznia kell legalább 8 karaktert, kis- és nagybetűt, számot és speciális karaktert.",
        'en' => "The password must contain at least 8 characters, including uppercase and lowercase letters, a number, and a special character.",
      ],
      'comparePw' => [
        'hu' => "A két jelszó nem egyezik.",
        'en' => "The passwords do not match.",
      ],
      'phone' => [
        'hu' => "Érvénytelen telefonszám formátum.",
        'en' => "Invalid phone number format.",
      ],
      'noSpaces' => [
        'hu' => "A mező nem tartalmazhat szóközt.",
        'en' => "The field cannot contain spaces.",
      ],
      'num' => [
        'hu' => "A mezőnek számnak kell lennie.",
        'en' => "The field must be a number.",
      ],
      'hasNum' => [
        'hu' => "A mezőnek tartalmaznia kell legalább egy számot.",
        'en' => "The field must contain at least one number.",
      ],
      'hasUppercase' => [
        'hu' => "A mezőnek tartalmaznia kell legalább egy nagybetűt.",
        'en' => "The field must contain at least one uppercase letter.",
      ],
      'split' => [
        'hu' => "A mezőnek legalább két szót kell tartalmaznia.",
        'en' => "The field must contain at least two words.",
      ],
      'nullable' => [
        'hu' => "A mező kitöltése nem kötelező.",
        'en' => "This field is optional"
      ]

    ];

    return $messages[$validator][$lang] ?? "Valdiációs hiba, a validátor vagy hibaüzenet nem létezik.";
  }
}
