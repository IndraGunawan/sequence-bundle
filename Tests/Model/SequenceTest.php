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

namespace Indragunawan\SequenceBundle\Tests;

use Indragunawan\SequenceBundle\Model\Sequence;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class SequenceTest extends TestCase
{
    public function testSequenceModel()
    {
        $sequence = new Sequence();
        $sequence->setName('order');
        $sequence->setFormat(null);
        $sequence->setLastValue(100);
        $sequence->setStartValue(1);
        $sequence->setIncrementBy(2);
        $sequence->setIncrementBy(2);
        $sequence->setResetEvery(60);

        $lastReset = new \DateTime();
        $sequence->setLastReset($lastReset);

        self::assertSame('order', $sequence->getName());
        self::assertNull($sequence->getFormat());
        self::assertSame(100, $sequence->getLastValue());
        self::assertSame(1, $sequence->getStartValue());
        self::assertSame(2, $sequence->getIncrementBy());
        self::assertSame(60, $sequence->getResetEvery());
        self::assertSame($lastReset, $sequence->getLastReset());
    }
}
