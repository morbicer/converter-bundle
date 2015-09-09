<?php

namespace Sakiro\ConverterBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SakiroConverterExtension extends Extension
{
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        foreach ($config['providers'] as $name => $args) {
            if ($name == 'chain') {
                $chain = $this->addProvider('chain');
                foreach ($config['providers']['chain'] as $name) {
                    if ($this->container->hasDefinition('sakiro_converter.provider.'.$name)) {
                        $chain->addMethodCall('addProvider', array($this->container->getDefinition('sakiro_converter.provider.'.$name)));
                    }
                }
            }
            else {
                $this->addProvider($name, $args);
            }
        }

        $defaultProvider = $this->container->getDefinition('sakiro_converter.provider.'.$config['default_provider']);
        $this->container->setDefinition('sakiro_converter.provider.default', $defaultProvider);
    }




    protected function addProvider($name, array $arguments = array())
    {
        $provider = new Definition('%sakiro_converter.provider.'.$name.'.class%', $arguments);

        $provider
            ->setPublic(false)
            ->addTag('sakiro_converter.provider');

        $this->container->setDefinition('sakiro_converter.provider.'.$name, $provider);
        return $provider;
    }
}
