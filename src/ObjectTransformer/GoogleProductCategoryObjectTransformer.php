<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


class GoogleProductCategoryObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($product, $item, $options = [])
    {
        $item->setGoogleCategory($product->getGoogleProductCategory());

        return $item;
    }
}