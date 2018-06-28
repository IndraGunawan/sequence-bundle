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

namespace Indragunawan\SequenceBundle\DependencyInjection;

use Indragunawan\SequenceBundle\Model\SequenceInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * @author Indra Gunawan <hello@indra.my.id>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('indragunawan_sequence');

        $rootNode
            ->children()
            ->arrayNode('orm')
                ->isRequired()
                ->children()
                    ->scalarNode('class')
                        ->validate()
                            ->ifTrue(function ($v) {
                                return false === is_subclass_of($v, SequenceInterface::class);
                            })
                            ->thenInvalid(sprintf('The entity_class %%s must implement "%s" interface.', SequenceInterface::class))
                        ->end()
                        ->cannotBeOverwritten()
                        ->isRequired()
                        ->cannotBeEmpty()
                    ->end()
                    ->scalarNode('manager_name')->cannotBeEmpty()->defaultValue('default')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
