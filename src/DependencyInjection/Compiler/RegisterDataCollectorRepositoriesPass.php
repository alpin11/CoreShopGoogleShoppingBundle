<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterDataCollectorRepositoriesPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('coreshop.google_shopping.repositories')) {
            return;
        }

        if (!$container->hasDefinition('coreshop.google_shopping.data_collector.default')) {
            return;
        }

        $definition = $container->getDefinition('coreshop.google_shopping.data_collector.default');
        $repositories = $container->getParameter('coreshop.google_shopping.repositories') ?: [];

        foreach ($repositories as $repository) {
            $definition->addMethodCall('addRepository', [new Reference($repository['id']), $repository['priority']]);
        }
    }
}