<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use Pimcore\Model\Asset\Image;
use Vitalybaev\GoogleMerchant\Product;

class ProductImageObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct(string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = [])
    {
        $baseUrl = isset($options['base_url']) ? $options['base_url'] : $this->baseUrl;
        $image = $product->getImage();

        if ($image instanceof Image) {
            $imageUrl = $baseUrl . $image->getThumbnail(['width' => 500, 'height' => 'auto'])->getPath();

            $item->setImage($imageUrl);
        }

        foreach ($product->getImages() as $image) {
            if ($image instanceof Image) {
                continue;
            }

            $imageUrl = $baseUrl . $image->getThumbnail(['width' => 500, 'height' => 'auto'])->getPath();

            $item->setAdditionalImage($imageUrl);
        }

        return $item;
    }
}