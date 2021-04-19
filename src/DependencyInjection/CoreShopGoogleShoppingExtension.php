<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CoreShopGoogleShoppingExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('coreshop.google_shopping.repositories', $config['repositories']);
        $container->setParameter('coreshop.google_shopping.host', $config['host']);
        $container->setParameter('coreshop.google_shopping.protocol', $config['protocol']);
        $container->setParameter('coreshop.google_shopping.cdn_enabled', $config['cdn_enabled']);
        $container->setParameter('coreshop.google_shopping.base_url', sprintf("%s://%s", $config['protocol'], $config['host']));
    }
}