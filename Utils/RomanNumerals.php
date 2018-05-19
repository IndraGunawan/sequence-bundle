<?php

declare(strict_types=1);

/*
 * This file is part of the Indragunawan/sequence-bundle
 *
 * (c) Indra Gunawan <hello@indra.my.id>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indragunawan\SequenceBundle\Utils;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class RomanNumerals
{
    private static $romanValues = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];

    public static function toRoman(int $integer): string
    {
        if ($integer < 1 || $integer > 4999) {
            throw new \InvalidArgumentException('Input must be in the range 1 to 4999.');
        }

        $roman = '';
        foreach (self::$romanValues as $symbol => $value) {
            $roman .= str_repeat($symbol, (int) ($integer / $value));
            $integer %= $value;
        }

        return $roman;
    }
}
