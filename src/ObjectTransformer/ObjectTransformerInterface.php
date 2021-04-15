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
     * @return Feed|Product
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = []);
}