<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler;


use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

class RegisterDistributorsPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'coreshop.google_shopping.distributor',
            'coreshop.google_shopping.distributor.composite',
            'coreshop.google_shopping.distributor',
            'addDistributor'
        );
    }
}