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

namespace Indragunawan\SequenceBundle\Tests\Services;

use Indragunawan\SequenceBundle\Exception\SequenceNotFoundException;
use Indragunawan\SequenceBundle\Services\SequenceManager;
use Indragunawan\SequenceBundle\Tests\Fixtures\SequenceTestEntity;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class SequenceManagerTest extends TestCase
{
    public function testNotFoundSequence()
    {
        $this->expectException(SequenceNotFoundException::class);
        $this->expectExceptionMessage('Sequence not found: "order".');

        $provider = $this->createMock('Indragunawan\SequenceBundle\Provider\SequenceProviderInterface');
        $provider
            ->expects($this->once())
            ->method('getSequence')
            ->with('order', [], true)
            ->willReturn(null);

        $replacer = $this->createMock('Indragunawan\SequenceBundle\Utils\PlaceholderReplacer');

        $manager = new SequenceManager($provider, $replacer);
        $manager->getNextValue('order');
    }

    public function testSequenceNextValue()
    {
        $seq = new SequenceTestEntity(1);
        $seq
            ->setStartValue(10)
            ->setIncrementBy(5);

        $provider = $this->createMock('Indragunawan\SequenceBundle\Provider\SequenceProviderInterface');
        $provider
            ->expects($this->once())
            ->method('getSequence')
            ->with('order', [], true)
            ->willReturn($seq);

        $replacer = $this->createMock('Indragunawan\SequenceBundle\Utils\PlaceholderReplacer');
        $replacer
            ->expects($this->once())
            ->method('replacePlaceholder')
            ->with(null, 10, [])
            ->willReturn("10");

        $manager = new SequenceManager($provider, $replacer);
        $nextVal = $manager->getNextValue('order');

        self::assertSame('10', $nextVal);
        self::assertSame(10, $seq->getLastValue());
    }

    public function testSequenceNextValueWithLastValue()
    {
        $seq = new SequenceTestEntity(1);
        $seq
            ->setStartValue(10)
            ->setLastValue(13)
            ->setIncrementBy(5);

        $provider = $this->createMock('Indragunawan\SequenceBundle\Provider\SequenceProviderInterface');
        $provider
            ->expects($this->once())
            ->method('getSequence')
            ->with('order', [], true)
            ->willReturn($seq);

        $replacer = $this->createMock('Indragunawan\SequenceBundle\Utils\PlaceholderReplacer');
        $replacer
            ->expects($this->once())
            ->method('replacePlaceholder')
            ->with(null, 18, [])
            ->willReturn("18");

        $manager = new SequenceManager($provider, $replacer);
        $nextVal = $manager->getNextValue('order');

        self::assertSame('18', $nextVal);
        self::assertSame(18, $seq->getLastValue());
    }

    public function testSequenceLastValue()
    {
        $seq = new SequenceTestEntity(1);
        $seq
            ->setStartValue(10)
            ->setLastValue(13)
            ->setIncrementBy(5);

        $provider = $this->createMock('Indragunawan\SequenceBundle\Provider\SequenceProviderInterface');
        $provider
            ->expects($this->once())
            ->method('getSequence')
            ->with('order', [], false)
            ->willReturn($seq);

        $replacer = $this->createMock('Indragunawan\SequenceBundle\Utils\PlaceholderReplacer');
        $replacer
            ->expects($this->once())
            ->method('replacePlaceholder')
            ->with(null, 13, [])
            ->willReturn("13");

        $manager = new SequenceManager($provider, $replacer);
        $nextVal = $manager->getLastValue('order');

        self::assertSame('13', $nextVal);
        self::assertSame(13, $seq->getLastValue());
    }

    public function testResetSequence()
    {
        $seq = new SequenceTestEntity(1);
        $seq
            ->setStartValue(10)
            ->setLastValue(13)
            ->setIncrementBy(5);

        $provider = $this->createMock('Indragunawan\SequenceBundle\Provider\SequenceProviderInterface');
        $provider
            ->expects($this->once())
            ->method('getSequence')
            ->with('order', [])
            ->willReturn($seq);

        $replacer = $this->createMock('Indragunawan\SequenceBundle\Utils\PlaceholderReplacer');

        $manager = new SequenceManager($provider, $replacer);
        $manager->resetSequence('order');

        self::assertNull($seq->getLastValue());
    }
}
