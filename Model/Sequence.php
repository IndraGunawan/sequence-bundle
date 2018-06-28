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

namespace Indragunawan\SequenceBundle\Model;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class Sequence implements SequenceInterface
{
    protected $name;
    protected $format;
    protected $lastValue;
    protected $startValue;
    protected $incrementBy;
    protected $lastReset;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): SequenceInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): SequenceInterface
    {
        $this->format = $format;

        return $this;
    }

    public function getLastValue(): ?int
    {
        return $this->lastValue;
    }

    public function setLastValue(?int $lastValue): SequenceInterface
    {
        $this->lastValue = $lastValue;

        return $this;
    }

    public function getStartValue(): ?int
    {
        return $this->startValue;
    }

    public function setStartValue(int $startValue): SequenceInterface
    {
        $this->startValue = $startValue;

        return $this;
    }

    public function getIncrementBy(): ?int
    {
        return $this->incrementBy;
    }

    public function setIncrementBy(int $incrementBy): SequenceInterface
    {
        $this->incrementBy = $incrementBy;

        return $this;
    }

    public function getLastReset(): ?\DateTime
    {
        return $this->lastReset;
    }

    public function setLastReset(?\DateTime $lastReset): SequenceInterface
    {
        $this->lastReset = $lastReset;

        return $this;
    }
}
