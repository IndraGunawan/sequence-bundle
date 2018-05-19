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

namespace Indragunawan\SequenceBundle\Tests;

use Indragunawan\SequenceBundle\IndragunawanSequenceBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class IndragunawanSequenceBundleTest extends TestCase
{
    public function testBuild()
    {
        $container = new ContainerBuilder();

        $compilerPassCount = count($container->getCompilerPassConfig()->getPasses());

        $bundle = new IndragunawanSequenceBundle();
        $bundle->build($container);

        self::assertSame($compilerPassCount + 1, count($container->getCompilerPassConfig()->getPasses()));
    }
}
