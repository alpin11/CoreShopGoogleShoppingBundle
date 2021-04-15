<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use Vitalybaev\GoogleMerchant\Product;

class DefaultObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = [])
    {
        $item->setId($product->getId());
        $item->setTitle($product->getName($options['locale']));
        $item->setDescription(strip_tags($product->getShortDescription($options['locale'])));
        $item->setBrand($product->getManufacturer()->getName());
        $item->setGtin($product->getEan());
        $item->setMpn($product->getEan());
        $item->setCondition($product->getCondition());

        return $item;
    }
}