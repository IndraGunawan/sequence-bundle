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

namespace Indragunawan\SequenceBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Indragunawan\SequenceBundle\Model\Sequence as BaseSequence;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 *
 * @ORM\Entity
 */
class SequenceTestEntity extends BaseSequence
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(nullable=true)
     */
    private $org;

    /**
     * @ORM\Column()
     */
    protected $name;

    /**
     * @ORM\Column(nullable=true)
     */
    protected $format;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $lastValue;

    /**
     * @ORM\Column(type="integer")
     */
    protected $startValue;

    /**
     * @ORM\Column(type="integer")
     */
    protected $incrementBy;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $resetEvery;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastReset;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setOrg(?string $org): self
    {
        $this->org = $org;

        return $this;
    }

    public function getOrg(): ?string
    {
        return $this->org;
    }
}
