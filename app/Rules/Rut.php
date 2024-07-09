<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Rut implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {


        if (!self::validarRUT($value)) {
            $fail('El campo :attribute debe ser un RUT válido.');
        }
    }

    private function validarRUT($value): bool
    {
        $arrValue = explode("-", $value);
        
        if(count($arrValue) < 2) {
            return false;
        }

        $rut = $arrValue[0];
        $dv = $arrValue[1];
        $rutInv = strrev($rut);
        $cant = strlen($rutInv);

        $arr = [];
        $i = 0;
        while($i < $cant)
        {
            $arr[$i] = substr($rutInv, $i, 1);
            $i ++;
        }

        $ca = count($arr);
        $m = 2;
        $c2 = 0;
        $suma = 0;

        while($c2 < $ca){
            $suma = $suma + ($arr[$c2] * $m);
            if($m == 7) {
                $m = 2;
            } else {
                $m ++;
            }
            $c2 ++;
        }

        $resto = $suma % 11;
        $digito = 11 - $resto;

        if($digito == 10) {
            $digito = "K";
        } else {
            if($digito == 11) {
                $digito = 0;
            }
        }

        if($dv == $digito) {
            return true;
        } else {
            return false;
        }
    }
}
