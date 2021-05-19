<?php


namespace App\Service\VerificationSiret;


class sfValidatorSiret extends sfValidatorBase
{
  protected function doClean($values)
  {
    $siret = trim($values);
    if(empty($siret) || strlen($siret) != 14)
    {
      throw new sfValidatorError($this, 'Le numéro siret est invalide');
    }
    $sum = 0;
    for($i=0; $i<14; $i++)
    {
      if($i%2 == 0)
      {
        $tmp = $siret[$i]*2;
        $tmp = $tmp > 9 ? $tmp - 9 : $tmp;
      }
      else
      {
        $tmp= $siret[$i];
      }
      $sum += $tmp;
    }
    if($sum%10 !== 0)
    {
      throw new sfValidatorError($this, 'Le numéro siret est invalide');
    }
    return $siret;
  }
}