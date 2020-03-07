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

namespace Indragunawan\SequenceBundle\Command;

use Doctrine\ORM\EntityManager;
use Indragunawan\SequenceBundle\Services\SequenceManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class ResetCounterCommand extends Command
{
    protected static $defaultName = 'indragunawan:sequence:reset-counter';
    private $em;
    private $sequenceManager;

    public function __construct(EntityManager $em, SequenceManagerInterface $sequenceManager)
    {
        parent::__construct();

        $this->em = $em;
        $this->sequenceManager = $sequenceManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Reset the sequences counter when it is time.')
            ->addArgument('sequence_name', InputArgument::REQUIRED, 'Sequence name.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $sequenceName = $input->getArgument('sequence_name');

        $this->em->transactional(function () use ($sequenceName) {
            $this->sequenceManager->resetSequence($sequenceName);
        });

        $io->success(sprintf('"%s" sequence successfully reset.', $sequenceName));

        return 0;
    }
}
