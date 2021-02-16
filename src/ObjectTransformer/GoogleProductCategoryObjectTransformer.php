<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


class GoogleProductCategoryObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($product, $item, $options = [])
    {
        if (!empty($product->getCategories())){
            $item->setGoogleCategory($product->getCategories()[0]->getGoogleCategoryId() ? $product->getCategories()[0]->getGoogleCategoryId() : $product->getGoogleProductCategory());
        }

        return $item;
    }
}