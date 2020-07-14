<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use Pimcore\Model\Asset\Image;

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
        $item->setImage($product->getImage() instanceof Image ? $product->getImage()->getFullPath() : null);
        $item->setBrand($product->getManufacturer()->getName());
        $item->setGtin($product->getEan());
        $item->setMpn($product->getEan());
        $item->setCondition($product->getCondition());

        return $item;
    }
}