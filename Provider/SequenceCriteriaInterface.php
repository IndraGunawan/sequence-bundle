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

use Doctrine\Common\Collections\Criteria;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
interface SequenceCriteriaInterface
{
    /**
     * Custom criteria.
     *
     * @param string $name
     * @param array  $criteria
     *
     * @return Criteria
     */
    public function getSequenceCriteria(string $name, array $criteria = []): Criteria;
}
