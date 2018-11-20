<?php

namespace App\Http\Services;



class ValidationService
{

  function __construct()
  {
    // code...
  }

  public function isDateStringValid(string $date, $format = 'Y-m-d'): bool
  {
    $d = \DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
  }
}
