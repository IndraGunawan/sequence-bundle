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

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
interface SequenceManagerInterface
{
    /**
     * Get next value of a sequence.
     *
     * @param string $name
     * @param array  $replacementPlaceholders
     * @param array  $criteria
     *
     * @return string
     */
    public function getNextValue(string $name, array $replacementPlaceholders = [], array $criteria = []): string;

    /**
     * Get last value of a sequence.
     *
     * @param string $name
     * @param array  $replacementPlaceholders
     * @param array  $criteria
     *
     * @return string
     */
    public function getLastValue(string $name, array $replacementPlaceholders = [], array $criteria = []): string;
}
