<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use Pimcore\Model\Asset\Image;
use Vitalybaev\GoogleMerchant\Product;

class ProductImageObjectTransformer implements ObjectTransformerInterface
{

    private string $baseUrl;
    private bool $cdnEnabled;

    public function __construct(bool $cdnEnabled, string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl;
        $this->cdnEnabled = $cdnEnabled;
    }

    /**
     * @inheritDoc
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = [])
    {
        $baseUrl = $options['base_url'] ?? $this->baseUrl;
        $image = $product->getImage();

        if ($image instanceof Image) {
            $thumbnailUrl = $image->getThumbnail(['width' => 500, 'height' => 'auto'])->getPath();

            if (!$this->cdnEnabled) {
                $thumbnailUrl = $baseUrl . $thumbnailUrl;
            }

            $item->setImage($thumbnailUrl);
        }

        foreach ($product->getImages() as $image) {
            if (!$image instanceof Image) {
                continue;
            }

            $thumbnailUrl = $image->getThumbnail(['width' => 500, 'height' => 'auto'])->getPath();

            if (!$this->cdnEnabled) {
                $thumbnailUrl = $baseUrl . $thumbnailUrl;
            }

            $item->setAdditionalImage($thumbnailUrl);
        }

        return $item;
    }
}
