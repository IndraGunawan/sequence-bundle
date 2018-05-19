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

namespace Indragunawan\SequenceBundle\Utils;

interface PlaceholderReplacerInterface
{
    /**
     * Replace placeholder with actual values.
     *
     * @param string|null $placeholderText
     * @param int         $number
     * @param array       $replacementPlaceholders
     *
     * @return string
     */
    public function replacePlaceholder(?string $placeholderText, int $number, array $replacementPlaceholders = []): string;
}
