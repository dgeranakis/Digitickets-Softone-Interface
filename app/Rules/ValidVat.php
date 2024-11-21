<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidVat implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = str_replace('0123456789', '', $value);
        if (strtoupper(substr($value, 0, 2)) == 'EL') $value = substr($value, 2);

        if (strlen($value) != 9 || !preg_match('/^\d+$/', $value)) {
            $fail('The :attribute field is not in the correct format.');
        };

        $sum = (substr($value, 0, 1) * 256) + (substr($value, 1, 1) * 128) + (substr($value, 2, 1) * 64) + (substr($value, 3, 1) * 32) +
            (substr($value, 4, 1) * 16) + (substr($value, 5, 1) * 8) + (substr($value, 6, 1) * 4) + (substr($value, 7, 1) * 2);

        $remain = $sum % 11;
        if ($remain == 10) $remain = 0;

        if (substr($value, 8, 1) != $remain) {
            $fail('The :attribute field does not contain a valid VAT number.');
        };
    }
}
