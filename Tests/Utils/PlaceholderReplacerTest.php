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

use Indragunawan\SequenceBundle\Utils\PlaceholderReplacer;
use Indragunawan\SequenceBundle\Utils\RomanNumerals;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class PlaceholderReplacerTest extends TestCase
{
    public function testNullOrEmptyText()
    {
        $replacer = new PlaceholderReplacer();

        self::assertSame('1', $replacer->replacePlaceholder(null, 1));
        self::assertSame('2', $replacer->replacePlaceholder('', 2));
    }

    public function testReplacementNotProvided()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No value provided for placeholder "XYZ", did you mean "ABC"?');

        $replacer = new PlaceholderReplacer();

        $replacer->replacePlaceholder('INV/{{NUMBER}}/{{XYZ}}', 1, ['ABC' => 'abc']);
    }

    public function testReplacementPlaceholder()
    {
        $replacer = new PlaceholderReplacer();

        self::assertSame('INV/001/abcVal', $replacer->replacePlaceholder('INV/{{NUMBER|3|0}}/{{ABC}}', 1, ['ABC' => 'abcVal']));
        self::assertSame('INV/001/--abcVal', $replacer->replacePlaceholder('INV/{{NUMBER|3|0}}/{{ABC|8|-}}', 1, ['ABC' => 'abcVal']));
        self::assertSame('INV/001/-abcval-', $replacer->replacePlaceholder('INV/{{NUMBER|3|0}}/{{ABC|lower|8|-|2}}', 1, ['ABC' => 'ABCVAL']));
        self::assertSame('INV/001/abcValXyzVal', $replacer->replacePlaceholder('INV/{{NUMBER|3|0}}/{{ABC}}{{XYZ}}', 1, ['ABC' => 'abcVal', 'XYZ' => 'XyzVal']));
    }

    public function testReplacementPadPosition()
    {
        $replacer = new PlaceholderReplacer();

        self::assertSame('INV/AAAA1', $replacer->replacePlaceholder('INV/{{NUMBER|5|A}}', 1)); // default pad left
        self::assertSame('INV/AAAA1', $replacer->replacePlaceholder('INV/{{NUMBER|5|A|0}}', 1)); // pad left
        self::assertSame('INV/1AAAA', $replacer->replacePlaceholder('INV/{{NUMBER|5|A|1}}', 1)); // pad right
        self::assertSame('INV/AA1AA', $replacer->replacePlaceholder('INV/{{NUMBER|5|A|2}}', 1)); // pad both
    }

    public function testReplacementTransform()
    {
        $replacer = new PlaceholderReplacer();

        self::assertSame('INV/aaaa1', $replacer->replacePlaceholder('INV/{{ NUMBER|lower|5|A }}', 1));
        self::assertSame('INV/A ba2', $replacer->replacePlaceholder('INV/{{ NUMBER|ucfirst|5|a b }}', 2));
        self::assertSame('INV/AA2AA', $replacer->replacePlaceholder('INV/{{ NUMBER|upper|5|a|2 }}', 2));
        self::assertSame('INV/A Ba 1', $replacer->replacePlaceholder('INV/{{ NUMBER|ucwords|6|a b }}', 1));
        self::assertSame('INV/A ba2', $replacer->replacePlaceholder('INV/{{ NUMBER|ucfirst|5|a b }}', 2));
        self::assertSame('INV/a BA2', $replacer->replacePlaceholder('INV/{{ NUMBER|lcfirst|5|A B }}', 2));
    }

    public function testDateReplacement()
    {
        $replacer = new PlaceholderReplacer();

        self::assertSame('INV/1/'.date('y'), $replacer->replacePlaceholder('INV/{{ NUMBER }}/{{y}}', 1));
        self::assertSame('INV/2/'.date('m'), $replacer->replacePlaceholder('INV/{{ NUMBER }}/{{m}}', 2));
        self::assertSame('INV/3/'.date('d'), $replacer->replacePlaceholder('INV/{{ NUMBER }}/{{d}}', 3));
    }

    public function testRomanDateReplacement()
    {
        $replacer = new PlaceholderReplacer();

        self::assertSame('INV/1/'.RomanNumerals::toRoman((int) date('y')), $replacer->replacePlaceholder('INV/{{ NUMBER }}/{{Ry}}', 1));
        self::assertSame('INV/2/'.RomanNumerals::toRoman((int) date('j')), $replacer->replacePlaceholder('INV/{{ NUMBER }}/{{Rj}}', 2));
        self::assertSame('INV/3/'.RomanNumerals::toRoman((int) date('n')), $replacer->replacePlaceholder('INV/{{ NUMBER }}/{{Rn}}', 3));
    }
}
