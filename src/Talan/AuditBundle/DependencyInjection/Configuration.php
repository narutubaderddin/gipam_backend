<?php

namespace App\Talan\AuditBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
       $builder = new TreeBuilder('talan_audit');
       $builder->getRootNode()
           ->children()
           ->scalarNode('table_prefix')->defaultValue('')->end()
           ->scalarNode('table_suffix')->defaultValue('_audit')->end()
           ->scalarNode('revision_field_name')->defaultValue('rev')->end()
           ->scalarNode('revision_type_field_name')->defaultValue('revtype')->end()
           ->scalarNode('revision_table_name')->defaultValue('revisions')->end()
           ->scalarNode('revision_id_field_type')->defaultValue('integer')->end()
           ->end();
       return $builder;
    }
}