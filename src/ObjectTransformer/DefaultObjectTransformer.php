<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


class DefaultObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($product, $item, $options = [])
    {
        $item->setId($product->getId());
        $item->setTitle($product->getName($options['locale']));
        $item->setDescription(strip_tags($product->getDescription($options['locale'])));
        $item->setBrand($product->getManufacturer()->getName());
        $item->setGtin($product->getEan());
        $item->setMpn($product->getEan());
        $item->setCondition($product->getCondition());

        return $item;
    }
}