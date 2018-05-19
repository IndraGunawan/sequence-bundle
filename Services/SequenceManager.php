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

namespace Indragunawan\SequenceBundle\Services;

use Indragunawan\SequenceBundle\Exception\SequenceNotFoundException;
use Indragunawan\SequenceBundle\Model\SequenceInterface;
use Indragunawan\SequenceBundle\Provider\SequenceProviderInterface;
use Indragunawan\SequenceBundle\Utils\PlaceholderReplacerInterface;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
final class SequenceManager implements SequenceManagerInterface
{
    private $provider;
    private $placeholderReplacer;

    public function __construct(SequenceProviderInterface $provider, PlaceholderReplacerInterface $placeholderReplacer)
    {
        $this->provider = $provider;
        $this->placeholderReplacer = $placeholderReplacer;
    }

    public function getNextValue(string $name, array $replacementPlaceholders = [], array $criteria = []): string
    {
        $sequence = $this->getSequence($name, $criteria);

        $nextSequenceNumber = $this->getSequenceNextValue($sequence);
        $value = $this->placeholderReplacer->replacePlaceholder($sequence->getFormat(), $nextSequenceNumber, $replacementPlaceholders);

        $sequence->setLastValue($nextSequenceNumber);

        return (string) $value;
    }

    public function getTransactionalNextValue(string $name, array $replacementPlaceholders = [], array $criteria = []): string
    {
        return $this->provider->transactional(function () use ($name, $replacementPlaceholders, $criteria) {
            return $this->getNextValue($name, $replacementPlaceholders, $criteria);
        });
    }

    public function getLastValue(string $name, array $replacementPlaceholders = [], array $criteria = []): string
    {
        $sequence = $this->getSequence($name, $criteria, false);

        return $this->placeholderReplacer->replacePlaceholder($sequence->getFormat(), $this->getSequenceLastValue($sequence), $replacementPlaceholders);
    }

    private function getSequence(string $name, array $criteria = [], bool $lock = true)
    {
        $sequence = $this->provider->getSequence($name, $criteria, $lock);
        if (null === $sequence) {
            throw new SequenceNotFoundException($name);
        }

        return $sequence;
    }

    private function getSequenceLastValue(SequenceInterface $sequence): int
    {
        return $sequence->getLastValue() ?: $sequence->getStartValue();
    }

    private function getSequenceNextValue(SequenceInterface $sequence): int
    {
        if (!$sequence->getLastValue()) {
            return $sequence->getStartValue();
        }

        return $sequence->getLastValue() + ($sequence->getIncrementBy() ?: 1);
    }
}
