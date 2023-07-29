<?php

namespace NetkodBilisim;

class TrTaxNumberValidation
{
    protected static array $cache = [];

    public static function validate(string $tax_number): bool
    {
        if (isset(static::$cache[$tax_number])) {
            return static::$cache[$tax_number];
        }

        if (strlen($tax_number) !== 10) {
            return static::$cache[$tax_number] = false;
        }

        $array = array_map(function ($value) {
            return intval($value);
        }, str_split($tax_number));

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $mod = ($array[$i] + (9 - $i)) % 10;
            $pow = $mod * pow(2, 9 - $i) % 9;
            $sum += ($mod !== 0 && $pow === 0) ? 9 : $pow;
        }
        $checksum = ($sum % 10 === 0) ? 0 : (10 - ($sum % 10));

        return static::$cache[$tax_number] = $checksum === $array[9];
    }
}
