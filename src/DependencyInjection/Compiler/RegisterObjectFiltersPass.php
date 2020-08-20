<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler;


use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

class RegisterObjectFiltersPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'coreshop.google_shopping.object_filter',
            'coreshop.google_shopping.object_filter.composite',
            'coreshop.google_shopping.object_filter',
            'addFilter'
        );
    }
}