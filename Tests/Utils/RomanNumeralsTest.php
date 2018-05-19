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

namespace Indragunawan\SequenceBundle\Tests\Utils;

use Indragunawan\SequenceBundle\Utils\RomanNumerals;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class RomanNumeralsTest extends TestCase
{
    public function testToRoman()
    {
        self::assertSame('IV', RomanNumerals::toRoman(4));
        self::assertSame('IX', RomanNumerals::toRoman(9));
        self::assertSame('M', RomanNumerals::toRoman(1000));
        self::assertSame('MMXVIII', RomanNumerals::toRoman(2018));
        self::assertSame('MMMMCMXCIX', RomanNumerals::toRoman(4999));
    }

    public function testLowerThan1()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input must be in the range 1 to 4999.');

        RomanNumerals::toRoman(0);
    }

    public function testGreaterThan4999()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Input must be in the range 1 to 4999.');

        RomanNumerals::toRoman(5000);
    }
}
