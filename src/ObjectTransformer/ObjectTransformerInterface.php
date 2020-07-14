<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;

interface ObjectTransformerInterface
{
    /**
     * @param GoogleShoppingProductInterface|GoogleShoppingProductInterface[] $product
     * @param Product $item
     * @param array $options
     * @return Feed
     */
    public function transform($product, $item, $options = []);
}