<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use CoreShop\Component\Pimcore\Templating\Helper\LinkGeneratorHelperInterface;
use Pimcore\Tool;
use Vitalybaev\GoogleMerchant\Product;

class ProductUrlObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @var LinkGeneratorHelperInterface
     */
    private $linkGeneratorHelper;
    /**
     * @var string|null
     */
    private $baseUrl;

    /**
     * ProductUrlObjectTransformer constructor.
     * @param LinkGeneratorHelperInterface $linkGeneratorHelper
     * @param string|null $baseUrl
     */
    public function __construct(LinkGeneratorHelperInterface $linkGeneratorHelper, string $baseUrl = null)
    {
        $this->linkGeneratorHelper = $linkGeneratorHelper;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = [])
    {
        $baseUrl = isset($options['base_url']) ? $options['base_url'] : $this->baseUrl;
        $locale = isset($options['locale']) ? $options['locale'] : Tool::getDefaultLanguage();

        $link = $baseUrl . $this->linkGeneratorHelper->getPath($product, null, [
                '_locale' => $locale
            ]);

        $item->setLink($link);

        return $item;
    }

}