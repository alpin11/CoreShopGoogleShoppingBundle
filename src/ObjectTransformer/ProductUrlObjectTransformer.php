<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Component\Pimcore\Templating\Helper\LinkGeneratorHelperInterface;
use Pimcore\Tool;

class ProductUrlObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @var LinkGeneratorHelperInterface
     */
    private $linkGeneratorHelper;

    public function __construct(LinkGeneratorHelperInterface $linkGeneratorHelper)
    {
        $this->linkGeneratorHelper = $linkGeneratorHelper;
    }

    /**
     * @inheritDoc
     */
    public function transform($product, $item, $options = [])
    {
        $locale = isset($options['locale']) ? $options['locale'] : Tool::getDefaultLanguage();

        $item->setLink($this->linkGeneratorHelper->getUrl($product, null, [
            '_locale' => $locale
        ]));

        return $item;
    }

}