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

use Indragunawan\SequenceBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Indra Gunawan <hello@indra.my.id>
 */
class ConfigurationTest extends TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        unset($this->configuration, $this->processor);
    }

    public function testInvalidEntityClass()
    {
        $this->expectException(\Symfony\Component\Config\Definition\Exception\InvalidConfigurationException::class);
        $this->expectExceptionMessage('The entity_class "DateTime" must implement "Indragunawan\SequenceBundle\Model\SequenceInterface" interface.');

        $config = $this->processor->processConfiguration(
            $this->configuration,
            [
                [
                    'orm' => [
                        'class' => \DateTime::class,
                    ],
                ],
            ]
        );
    }
}
