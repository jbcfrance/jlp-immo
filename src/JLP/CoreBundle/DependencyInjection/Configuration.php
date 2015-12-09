<?php

namespace JLP\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jlp_core');

        $rootNode
            ->children()
                ->arrayNode('passerelle')
                    ->children()
                    ->scalarNode('zip_name')
                        ->isRequired()
                        ->info("Nom de l'archive contenant l'export du logiciel.")
                    ->end()
                    ->scalarNode('xml_filename')
                        ->isRequired()
                        ->info("Nom du fichier xml contenant les annonces.")
                        ->defaultValue('annonces.xml')
                    ->end()
                    ->scalarNode('xml_annonce_node')
                        ->isRequired()
                        ->info("Nom du noeud contentant une annonce dans le xml.")
                        ->defaultValue('annonce')
                    ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
