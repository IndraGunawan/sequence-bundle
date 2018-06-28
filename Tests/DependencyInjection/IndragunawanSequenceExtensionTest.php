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

namespace Indragunawan\SequenceBundle\Tests\DependencyInjection;

use Indragunawan\SequenceBundle\DependencyInjection\IndragunawanSequenceExtension;
use Indragunawan\SequenceBundle\Services\SequenceManagerInterface;
use Indragunawan\SequenceBundle\Tests\Fixtures\SequenceTestEntity;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class IndragunawanSequenceExtensionTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var IndragunawanSequenceExtension
     */
    private $extension;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->container = new ContainerBuilder();
        $this->extension = new IndragunawanSequenceExtension();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        unset($this->container, $this->extension);
    }

    public function testLoadedService()
    {
        $config = [
            [
                'orm' => [
                    'class' => SequenceTestEntity::class,
                ],
            ],
        ];

        $this->extension->load($config, $this->container);

        self::assertTrue($this->container->hasDefinition('indragunawan_sequence.sequence_manager'));
        self::assertTrue($this->container->hasAlias(SequenceManagerInterface::class));
    }
}
