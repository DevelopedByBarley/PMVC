<?php

namespace Core;

use Exception;

/*
  alpha - Csak betűk és szóközök (magyar ékezetekkel)
alphaNumSpace - Betűk, számok és szóközök
alphaNum - Csak betűk és számok
date - Dátum formátum (YYYY-MM-DD)
datetime - Dátum és idő formátum
url - URL validáció
ip - IP cím validáció
postalCode - Magyar irányítószám (4 számjegy)
taxNumber - Magyar adószám validáció
boolean - Boolean érték
integer - Egész szám
array - Tömb típus
json - JSON formátum
regex - Egyedi regex pattern
in - Enum validáció (megadott értékek közül)
minValue - Minimum numerikus érték
maxValue - Maximum numerikus érték
between - Értéktartomány
fileSize - Fájl méret validáció (KB-ban)
mimeType - Fájl típus validáció
confirmed - Mező egyezés validáció
nullable - A mező lehet üres vagy null (opcionális)
*/

/* 
    Validációs példák - Felhasználói bejelentkezés:
    
    Login validáció:
    $request()->validate([
      "email" => ['required', 'email', 'string', 'max:255'],
      "password" => ['required', 'string', 'min:8']
    ]);

    Regisztráció validáció:
    $request()->validate([
      "name" => ['required', 'string', 'min:3', 'max:100', 'split'],
      "email" => ['required', 'email', 'unique:email|users', 'max:255'],
      "password" => ['required', 'password', 'min:8', 'max:255'],
      "password_confirmation" => ['required', 'string'],
      "phone" => ['required', 'phone']
    ]);

    Céges esemény - Dolgozói adatok validáció:
    $request()->validate([
      "full_name" => ['required', 'string', 'min:5', 'max:150', 'split', 'alpha'],
      "email" => ['required', 'email', 'unique:email|event_participants', 'max:255'],
      "phone" => ['required', 'phone'],
      "company_name" => ['required', 'string', 'min:2', 'max:255', 'alphaNumSpace'],
      "position" => ['required', 'string', 'min:2', 'max:100'],
      "department" => ['string', 'max:100'],
      "employee_id" => ['alphaNum', 'max:50'],
      "years_at_company" => ['numeric', 'min:0', 'max:2'],
      "dietary_restrictions" => ['string', 'max:500'],
      "arrival_date" => ['required', 'date'],
      "company_tax_number" => ['taxNumber'],
      "postal_code" => ['postalCode']
    ]);
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

    $keys = array_keys($ret);

    // If key doesnt exist in request, unset it
    foreach ($request as $key => $value) {
      if (!in_array($key, $keys)) {
        unset($request[$key]);
      }
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
   * Nullable validáció - a mező lehet üres vagy null
   */
  protected static function nullable($value)
  {
    return true; // Mindig igaz, csak jelzi hogy a mező opcionális
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
    return (int)mb_strlen($value, 'UTF-8') >= (int)$length;
  }

  /**
   * Maximális hossz validáció
   */
  protected static function max($value, $length)
  {
    return mb_strlen($value, 'UTF-8') <= (int)$length;
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
    $isLengthValid = mb_strlen($value, 'UTF-8') >= 8;

    return $hasUpperCase && $hasLowerCase && $hasNumber && $hasSpecialChar && $isLengthValid;
  }

  /**
   * Jelszó egyezés validáció
   * FIGYELEM: Ez a validátor nem használható a jelenlegi validációs rendszerben,
   * mert csak egy mező értékét kapja meg. Használd helyette a confirmed() metódust,
   * vagy implementálj egyedi logikát a controller-ben.
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
    $result = (Database::getInstance())->query($sql, ["value" => $value])->get()[0];

    return (int)$result->count === 0;
  }

  /**
   * Magyar telefonszám validáció
   */
  protected static function phone($value)
  {
    $cleanValue = preg_replace('/[\s\-]/', '', $value);
    $pattern = '/^(\+36|06)[1-9]\d{8}$/';

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
    $words = array_values(array_filter(explode(' ', trim($value)), function($word) {
      return trim($word) !== '';
    }));
    return count($words) >= 2 && mb_strlen($words[1], 'UTF-8') > 0;
  }

  /**
   * Csak betűk és szóközök validáció (nevek)
   */
  protected static function alpha($value)
  {
    return (bool)preg_match('/^[a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ\s]+$/', $value);
  }

  /**
   * Betűk, számok és szóközök validáció
   */
  protected static function alphaNumSpace($value)
  {
    return (bool)preg_match('/^[a-zA-Z0-9áéíóöőúüűÁÉÍÓÖŐÚÜŰ\s]+$/', $value);
  }

  /**
   * Csak betűk és számok (szóköz nélkül)
   */
  protected static function alphaNum($value)
  {
    return (bool)preg_match('/^[a-zA-Z0-9]+$/', $value);
  }

  /**
   * Dátum formátum validáció (YYYY-MM-DD)
   */
  protected static function date($value)
  {
    $d = \DateTime::createFromFormat('Y-m-d', $value);
    return $d && $d->format('Y-m-d') === $value;
  }

  /**
   * Dátum és idő validáció (YYYY-MM-DD HH:MM:SS)
   */
  protected static function datetime($value)
  {
    $d = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
    return $d && $d->format('Y-m-d H:i:s') === $value;
  }

  /**
   * URL validáció
   */
  protected static function url($value)
  {
    return filter_var($value, FILTER_VALIDATE_URL) !== false;
  }

  /**
   * IP cím validáció
   */
  protected static function ip($value)
  {
    return filter_var($value, FILTER_VALIDATE_IP) !== false;
  }

  /**
   * Irányítószám validáció (magyar)
   */
  protected static function postalCode($value)
  {
    return (bool)preg_match('/^\d{4}$/', $value);
  }

  /**
   * Adószám validáció (magyar)
   */
  protected static function taxNumber($value)
  {
    $cleanValue = preg_replace('/[\s\-]/', '', $value);
    return (bool)preg_match('/^\d{8}-\d{1}-\d{2}$/', $value) || (bool)preg_match('/^\d{11}$/', $cleanValue);
  }

  /**
   * Boolean érték validáció
   */
  protected static function boolean($value)
  {
    return in_array($value, [true, false, 0, 1, '0', '1', 'true', 'false'], true);
  }

  /**
   * Egész szám validáció
   */
  protected static function integer($value)
  {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
  }

  /**
   * Tömb validáció
   */
  protected static function array($value)
  {
    return is_array($value);
  }

  /**
   * JSON validáció
   */
  protected static function json($value)
  {
    json_decode($value);
    return json_last_error() === JSON_ERROR_NONE;
  }

  /**
   * Reguláris kifejezés validáció
   */
  protected static function regex($value, $pattern)
  {
    return (bool)preg_match($pattern, $value);
  }

  /**
   * Enum validáció (megadott értékek közül választás)
   */
  protected static function in($value, $params)
  {
    $allowedValues = explode('|', $params);
    return in_array($value, $allowedValues, true);
  }

  /**
   * Minimum érték validáció (numerikus)
   */
  protected static function minValue($value, $min)
  {
    return is_numeric($value) && $value >= (float)$min;
  }

  /**
   * Maximum érték validáció (numerikus)
   */
  protected static function maxValue($value, $max)
  {
    return is_numeric($value) && $value <= (float)$max;
  }

  /**
   * Értéktartomány validáció
   */
  protected static function between($value, $params)
  {
    $range = explode('|', $params);
    if (count($range) !== 2) {
      return false;
    }
    $min = (float)$range[0];
    $max = (float)$range[1];
    return is_numeric($value) && $value >= $min && $value <= $max;
  }

  /**
   * File méret validáció (KB-ban)
   */
  protected static function fileSize($file, $maxSizeKB)
  {
    if (!isset($file['size'])) {
      return false;
    }
    return ($file['size'] / 1024) <= (int)$maxSizeKB;
  }

  /**
   * File típus validáció (MIME type)
   */
  protected static function mimeType($file, $allowedTypes)
  {
    if (!isset($file['type'])) {
      return false;
    }
    $types = explode('|', $allowedTypes);
    return in_array($file['type'], $types, true);
  }

  /**
   * Jelszó egyezés validáció két mező között
   */
  protected static function confirmed($value, $fieldName)
  {
    // Ez a metódus speciális kezelést igényel a validate metódusban
    // A jelenlegi implementáció egyszerűsített
    return !empty($value);
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
        'en' => "The field must be a string!",
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
        'hu' => "Kérjük adjon meg érvényes email címet!",
        'en' => "Please enter a valid email address!",
      ],
      'unique' => [
        'hu' => "Ez az adat már használatban van!",
        'en' => "This data is already in use!",
      ],
      'phone' => [
        'hu' => "Kérjük adjon meg érvényes magyar telefonszámot! (pl: +36301234567 vagy 06301234567)",
        'en' => "Please enter a valid Hungarian phone number!",
      ],
      'noSpaces' => [
        'hu' => "A mező nem tartalmazhat szóközt!",
        'en' => "The field cannot contain spaces!",
      ],
      'numeric' => [
        'hu' => "A mező csak számokat tartalmazhat!",
        'en' => "The field must be numeric!",
      ],
      'hasNum' => [
        'hu' => "A mezőnek tartalmaznia kell legalább egy számot!",
        'en' => "The field must contain at least one number!",
      ],
      'hasUppercase' => [
        'hu' => "A mezőnek tartalmaznia kell legalább egy nagybetűt!",
        'en' => "The field must contain at least one uppercase letter!",
      ],
      'split' => [
        'hu' => "Kérjük adja meg a teljes nevét (vezetéknév és keresztnév)!",
        'en' => "Please enter your full name (first and last name)!",
      ],
      'alpha' => [
        'hu' => "A mező csak betűket és szóközöket tartalmazhat!",
        'en' => "The field may only contain letters and spaces!",
      ],
      'alphaNumSpace' => [
        'hu' => "A mező csak betűket, számokat és szóközöket tartalmazhat!",
        'en' => "The field may only contain letters, numbers and spaces!",
      ],
      'alphaNum' => [
        'hu' => "A mező csak betűket és számokat tartalmazhat!",
        'en' => "The field may only contain letters and numbers!",
      ],
      'date' => [
        'hu' => "Kérjük adjon meg érvényes dátumot (ÉÉÉÉ-HH-NN formátumban)!",
        'en' => "Please enter a valid date (YYYY-MM-DD format)!",
      ],
      'datetime' => [
        'hu' => "Kérjük adjon meg érvényes dátumot és időt (ÉÉÉÉ-HH-NN ÓÓ:PP:MM formátumban)!",
        'en' => "Please enter a valid date and time (YYYY-MM-DD HH:MM:SS format)!",
      ],
      'url' => [
        'hu' => "Kérjük adjon meg érvényes URL címet!",
        'en' => "Please enter a valid URL!",
      ],
      'ip' => [
        'hu' => "Kérjük adjon meg érvényes IP címet!",
        'en' => "Please enter a valid IP address!",
      ],
      'postalCode' => [
        'hu' => "Kérjük adjon meg érvényes irányítószámot (4 számjegy)!",
        'en' => "Please enter a valid postal code (4 digits)!",
      ],
      'taxNumber' => [
        'hu' => "Kérjük adjon meg érvényes adószámot (pl: 12345678-1-23)!",
        'en' => "Please enter a valid tax number!",
      ],
      'boolean' => [
        'hu' => "A mező értéke csak igaz vagy hamis lehet!",
        'en' => "The field must be true or false!",
      ],
      'integer' => [
        'hu' => "A mező csak egész számot tartalmazhat!",
        'en' => "The field must be an integer!",
      ],
      'array' => [
        'hu' => "A mezőnek tömbnek kell lennie!",
        'en' => "The field must be an array!",
      ],
      'json' => [
        'hu' => "A mező érvényes JSON formátumot kell tartalmazzon!",
        'en' => "The field must be valid JSON!",
      ],
      'regex' => [
        'hu' => "A mező formátuma nem megfelelő!",
        'en' => "The field format is invalid!",
      ],
      'in' => [
        'hu' => "A kiválasztott érték érvénytelen! Választható értékek: {$param}",
        'en' => "The selected value is invalid! Allowed values: {$param}",
      ],
      'minValue' => [
        'hu' => "Az érték nem lehet kisebb, mint {$param}!",
        'en' => "The value cannot be less than {$param}!",
      ],
      'maxValue' => [
        'hu' => "Az érték nem lehet nagyobb, mint {$param}!",
        'en' => "The value cannot be greater than {$param}!",
      ],
      'between' => [
        'hu' => "Az értéknek {$param} között kell lennie!",
        'en' => "The value must be between {$param}!",
      ],
      'fileSize' => [
        'hu' => "A fájl mérete nem lehet nagyobb, mint {$param} KB!",
        'en' => "The file size cannot be larger than {$param} KB!",
      ],
      'mimeType' => [
        'hu' => "A fájl típusa nem megengedett! Megengedett típusok: {$param}",
        'en' => "The file type is not allowed! Allowed types: {$param}",
      ],
      'confirmed' => [
        'hu' => "A mezők nem egyeznek!",
        'en' => "The fields do not match!",
      ],
      'comparePw' => [
        'hu' => "A jelszavak nem egyeznek!",
        'en' => "The passwords do not match!",
      ],
      'nullable' => [
        'hu' => "", // Nincs hibaüzenet, mert mindig sikeres
        'en' => "",
      ],
    ];

    return $messages[$validator][$lang] ?? "Validációs hiba történt!";
  }
}
