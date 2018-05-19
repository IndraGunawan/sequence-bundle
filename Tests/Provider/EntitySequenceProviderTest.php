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

namespace Indragunawan\SequenceBundle\Tests\Provider;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\TransactionRequiredException;
use Indragunawan\SequenceBundle\Model\SequenceInterface;
use Indragunawan\SequenceBundle\Provider\EntitySequenceProvider;
use Indragunawan\SequenceBundle\Tests\Fixtures\SequenceTestEntity;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class EntitySequenceProviderTest extends TestCase
{
    public function testSequenceNotFound()
    {
        $em = DoctrineTestHelper::createTestEntityManager();
        $this->createSchema($em);

        $provider = new EntitySequenceProvider($this->getManager($em), SequenceTestEntity::class);

        self::assertNull($provider->getSequence('order', [], false));
    }

    public function testThrowExceptionIfNotInTransaction()
    {
        $this->expectException(TransactionRequiredException::class);
        $this->expectExceptionMessage('An open transaction is required for this operation.');

        $em = DoctrineTestHelper::createTestEntityManager();
        $this->createSchema($em);

        $provider = new EntitySequenceProvider($this->getManager($em), SequenceTestEntity::class);
        $provider->getSequence('order');
    }

    public function testSequenceFound()
    {
        $em = DoctrineTestHelper::createTestEntityManager();
        $this->createSchema($em);

        $seq = new SequenceTestEntity(1);
        $seq->setName('order');
        $seq->setStartValue(1);
        $seq->setIncrementBy(2);

        $em->persist($seq);
        $em->flush();

        $provider = new EntitySequenceProvider($this->getManager($em), SequenceTestEntity::class);

        $sequence = $em->transactional(function () use ($provider) {
            return $provider->getSequence('order', []);
        });

        self::assertInstanceOf(SequenceInterface::class, $sequence);
        self::assertSame('order', $sequence->getName());
        self::assertSame(1, $sequence->getStartValue());
        self::assertSame(2, $sequence->getIncrementBy());
    }

    public function testTransactional()
    {
        $em = DoctrineTestHelper::createTestEntityManager();
        $this->createSchema($em);

        $seq = new SequenceTestEntity(1);
        $seq->setName('order');
        $seq->setStartValue(1);
        $seq->setIncrementBy(2);

        $em->persist($seq);
        $em->flush();

        $provider = new EntitySequenceProvider($this->getManager($em), SequenceTestEntity::class);

        $sequence = $provider->transactional(function () use ($provider) {
            $seq = $provider->getSequence('order', []);

            return $seq->getName();
        });

        self::assertSame('order', $sequence);
    }

    public function testWithCriteria()
    {
        $em = DoctrineTestHelper::createTestEntityManager();
        $this->createSchema($em);

        $seq1 = new SequenceTestEntity(1);
        $seq1->setName('order');
        $seq1->setOrg('org1');
        $seq1->setStartValue(1);
        $seq1->setIncrementBy(2);

        $seq2 = new SequenceTestEntity(2);
        $seq2->setName('order');
        $seq2->setOrg('org2');
        $seq2->setStartValue(5);
        $seq2->setIncrementBy(10);

        $em->persist($seq1);
        $em->persist($seq2);
        $em->flush();

        $provider = new EntitySequenceProvider($this->getManager($em), SequenceTestEntity::class);

        $sequence = $em->transactional(function () use ($provider) {
            return $provider->getSequence('order', ['org' => 'org2']);
        });

        self::assertInstanceOf(SequenceInterface::class, $sequence);
        self::assertSame('order', $sequence->getName());
        self::assertSame(5, $sequence->getStartValue());
        self::assertSame(10, $sequence->getIncrementBy());
    }

    private function getManager($em, $name = null)
    {
        $manager = $this->getMockBuilder('Doctrine\Common\Persistence\ManagerRegistry')->getMock();
        $manager->expects($this->any())
            ->method('getManager')
            ->with($this->equalTo($name))
            ->will($this->returnValue($em));

        return $manager;
    }

    private function createSchema($em)
    {
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema([
            $em->getClassMetadata(SequenceTestEntity::class),
        ]);
    }
}
