<?php

namespace Core;

/* 
  --------- USAGE -------
   $faker = Faker::create();
   dd($faker->name());   
*/


class Faker
{
  // create static $faker;
  static $faker;

  // Create instance
  public static function create($lang = 'en_US')
  {
    if (!static::$faker) { // If doesn't exist instance
      static::$faker  = \Faker\Factory::create($lang);
    }

    return static::$faker;
  }
}
