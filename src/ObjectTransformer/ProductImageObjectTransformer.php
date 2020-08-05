<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use Pimcore\Model\Asset\Image;

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
    public function transform($product, $item, $options = [])
    {
        $baseUrl = isset($options['base_url']) ? $options['base_url'] : $this->baseUrl;
        $image = $product->getImage();

        if ($image instanceof Image) {
            $imageUrl = $baseUrl . $image->getThumbnail(['width' => 500, 'height' => 'auto'])->getPath();

            $item->setImage($imageUrl);
        }

        return $item;
    }
}