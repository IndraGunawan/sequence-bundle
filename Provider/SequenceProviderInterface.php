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

namespace Indragunawan\SequenceBundle\Provider;

use Indragunawan\SequenceBundle\Model\SequenceInterface;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
interface SequenceProviderInterface
{
    /**
     * Fetch the Sequence.
     */
    public function getSequence(string $name, array $criteria = [], bool $lock = true): ?SequenceInterface;
}
