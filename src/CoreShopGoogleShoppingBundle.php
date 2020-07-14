<?php


namespace CoreShop\Bundle\GoogleShoppingBundle;


use CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler\RegisterDataCollectorRepositoriesPass;
use CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler\RegisterDistributorsPass;
use CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler\RegisterObjectFiltersPass;
use CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler\RegisterObjectTransformersPass;
use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CoreShopGoogleShoppingBundle extends AbstractPimcoreBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterDataCollectorRepositoriesPass());
        $container->addCompilerPass(new RegisterObjectFiltersPass());
        $container->addCompilerPass(new RegisterObjectTransformersPass());
        $container->addCompilerPass(new RegisterDistributorsPass());
    }
}