<?php

namespace App\Talan\AuditBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
class TalanAuditExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        $loader = new YamlFileLoader($container,new FileLocator(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'config'));
        $loader->load('services.yaml');

        $configurables = array(
            'table_prefix',
            'table_suffix',
            'revision_field_name',
            'revision_type_field_name',
            'revision_table_name',
            'revision_id_field_type',
        );
        $params=[];
        foreach ($configurables as $key) {
            $params[$key]= $config[$key];
        }
        $container->setParameter('talan_audit.config' , $params);
    }
}