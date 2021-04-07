<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Pimcore\DynamicOptionsProvider;


use Pimcore\Cache;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use Symfony\Component\Config\FileLocatorInterface;

class ProductCategoryOptionsProvider implements SelectOptionsProviderInterface
{
    const CACHE_KEY = "google_shopping_taxonomy_with_ids";

    /**
     * @var FileLocatorInterface
     */
    private FileLocatorInterface $fileLocator;

    public function __construct(FileLocatorInterface $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    /**
     * @inheritDoc
     */
    public function getOptions($context, $fieldDefinition)
    {
        $options = [];

        if (($fromCache = Cache::load(self::CACHE_KEY))) {
            return $fromCache;
        }

        $sourcePath = $this->fileLocator->locate('@CoreShopGoogleShoppingBundle/Resources/google/taxonomy-with-ids.txt');
        $handle = fopen($sourcePath, 'r');

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $result = [];

                preg_match('/(\d+) - (.+)/', $line, $result);

                $options[] = [
                    'key' => $result[2],
                    'value' => $result[1]
                ];
            }

            fclose($handle);
        }

        Cache::save($options, self::CACHE_KEY);

        return $options;
    }

    /**
     * @inheritDoc
     */
    public function hasStaticOptions($context, $fieldDefinition)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultValue($context, $fieldDefinition)
    {
        return $fieldDefinition->getDefaultValue();
    }
}