<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use Vitalybaev\GoogleMerchant\Product;

class GoogleProductCategoryObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = [])
    {
        if (!empty($product->getCategories())){
            $item->setGoogleCategory($product->getCategories()[0]->getGoogleCategoryId() ? $product->getCategories()[0]->getGoogleCategoryId() : $product->getGoogleProductCategory());
        }

        return $item;
    }
}