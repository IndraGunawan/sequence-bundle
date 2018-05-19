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

namespace Indragunawan\SequenceBundle\Tests\Exception;

use Indragunawan\SequenceBundle\Exception\SequenceNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class SequenceNotFoundExceptionTest extends TestCase
{
    public function testExceptionMessage()
    {
        $exception = new SequenceNotFoundException('invoice');

        self::assertSame('Sequence not found: "invoice".', $exception->getMessage());
    }
}
