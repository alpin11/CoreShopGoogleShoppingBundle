<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DependencyInjection\Compiler;


use CoreShop\Bundle\PimcoreBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

class RegisterObjectTransformersPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'coreshop.google_shopping.object_transformer',
            'coreshop.google_shopping.object_transformer.composite',
            'coreshop.google_shopping.object_transformer',
            'addTransformer'
        );
    }
}