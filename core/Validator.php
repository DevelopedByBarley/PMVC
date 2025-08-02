<?php

namespace Core;

use Exception;

/* 
    What i want ?

    $request()->validators([
      "name" => ['string', 'required', 'min:5', 'max:5', 'uniq:email:users']
    ])
  */

class Validator
{
  protected $ret = [];

  /**
   * Strukturálja a validációs szabályokat
   */
  private static function structure($rules)
  {
    return $rules; // Egyszerűsítve, mivel csak visszaadjuk ugyanazt
  }


  /**
   * Fő validációs metódus
   */
  public static function validate($request, $rules)
  {
    $ret = [];
    $rules = static::structure($rules);

    foreach ($request as $req_key => $req_value) {
      $req_value = sanitize($req_value);
      $validator = $rules[$req_key] ?? [];
      
      foreach ($validator as $val_value) {
        $validationResult = static::executeValidator($req_value, $val_value);
        $ret[$req_key][$validationResult['name']] = $validationResult;
      }
    }

    $errors = static::errors($ret);
    if (!empty($errors)) {
      return ValidationException::throw($errors, $request);
    }

    return $request;
  }

  /**
   * Egyetlen validátor végrehajtása
   */
  private static function executeValidator($value, $rule)
  {
    if (strpos($rule, ':') !== false) {
      $parts = explode(":", $rule, 2); // Limit 2-re a biztonság kedvéért
      $validatorName = $parts[0];
      $validatorValue = $parts[1];
      
      $status = static::$validatorName($value, $validatorValue);
      
      return [
        'name' => $validatorName,
        'status' => $status,
        'errorMessage' => $status ? '' : static::errorMessages($validatorName, $validatorValue)
      ];
    } else {
      $status = static::$rule($value);
      
      return [
        'name' => $rule,
        'status' => $status,
        'errorMessage' => $status ? '' : static::errorMessages($rule)
      ];
    }
  }

  /**
   * A sikertelen validációk hibáinak összegyűjtése
   */
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

  // ================================
  // VALIDÁCIÓS SZABÁLYOK
  // ================================

  /**
   * Kötelező mező validáció
   */
  protected static function required($value)
  {
    return !empty($value) && $value !== '' && $value !== null;
  }
  /**
   * Szöveg típus validáció
   */
  protected static function string($value)
  {
    return is_string($value);
  }

  /**
   * Minimális hossz validáció
   */
  protected static function min($value, $length)
  {
    return strlen($value) >= (int)$length;
  }

  /**
   * Maximális hossz validáció
   */
  protected static function max($value, $length)
  {
    return strlen($value) <= (int)$length;
  }

  /**
   * Jelszó erősség validáció
   */
  protected static function password($value)
  {
    $hasUpperCase = preg_match('/[A-Z]/', $value);
    $hasLowerCase = preg_match('/[a-z]/', $value);
    $hasNumber = preg_match('/\d/', $value);
    $hasSpecialChar = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value);
    $isLengthValid = strlen($value) >= 8;

    return $hasUpperCase && $hasLowerCase && $hasNumber && $hasSpecialChar && $isLengthValid;
  }

  /**
   * Jelszó egyezés validáció
   */
  protected static function comparePw($password, $confirmPassword)
  {
    return $password === $confirmPassword;
  }

  /**
   * Egyediség validáció adatbázisban
   */
  protected static function unique($value, $params)
  {
    $paramsArray = explode('|', $params);

    if (count($paramsArray) < 2) {
      throw new Exception("Hibás bemenet: a paraméterek nem megfelelőek.");
    }

    $record = trim($paramsArray[0]); // Oszlopnév
    $db = trim($paramsArray[1]); // Táblanév

    $sql = "SELECT COUNT(*) as count FROM `$db` WHERE `$record` = :value";
    $result = (new Database)->query($sql, ["value" => $value])->get()[0];

    return (int)$result->count === 0;
  }

  /**
   * Magyar telefonszám validáció
   */
  protected static function phone($value)
  {
    $cleanValue = preg_replace('/[\s\-]/', '', $value);
    $pattern = '/^(?:\+36|06)\d{9}$/';

    return (bool)preg_match($pattern, $cleanValue);
  }

  /**
   * Email cím validáció
   */
  protected static function email($value)
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  /**
   * Szóköz mentes validáció
   */
  protected static function noSpaces($value)
  {
    return strpos($value, ' ') === false;
  }

  /**
   * Numerikus érték validáció
   */
  protected static function numeric($value)
  {
    return is_numeric($value);
  }

  /**
   * Szám tartalmazás validáció
   */
  protected static function hasNum($value)
  {
    return (bool)preg_match('/\d/', $value);
  }

  /**
   * Nagybetű tartalmazás validáció
   */
  protected static function hasUppercase($value)
  {
    return (bool)preg_match('/[A-Z]/', $value);
  }

  /**
   * Legalább két szó validáció (teljes név)
   */
  protected static function split($value)
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
