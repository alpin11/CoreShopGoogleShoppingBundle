<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler;


use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

class RegisterObjectFiltersPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'coreshop.object_filter',
            'coreshop.object_filter.composite',
            'coreshop.object_filter',
            'addFilter'
        );
    }
}