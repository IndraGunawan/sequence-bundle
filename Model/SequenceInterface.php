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
interface SequenceInterface
{
    public function getName(): ?string;

    public function getFormat(): ?string;

    public function getLastValue(): ?int;

    public function getStartValue(): ?int;

    public function getIncrementBy(): ?int;

    public function getResetEvery(): ?int;
}
