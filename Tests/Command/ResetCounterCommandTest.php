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

namespace Indragunawan\SequenceBundle\Tests\Command;

use Indragunawan\SequenceBundle\Command\ResetCounterCommand;
use Indragunawan\SequenceBundle\Services\SequenceManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Test\DoctrineTestHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class ResetCounterCommandTest extends TestCase
{
    public function testExecute()
    {
        $em = DoctrineTestHelper::createTestEntityManager();

        $sequenceManager = $this->createMock(SequenceManagerInterface::class);
        $sequenceManager->expects(self::once())->method('resetSequence');

        $application = new Application();
        $application->add(new ResetCounterCommand($em, $sequenceManager));

        $command = $application->find('indragunawan:sequence:reset-counter');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'sequence_name' => 'order',
        ]);

        self::assertStringContainsString('"order" sequence successfully reset.', $commandTester->getDisplay());
    }
}
