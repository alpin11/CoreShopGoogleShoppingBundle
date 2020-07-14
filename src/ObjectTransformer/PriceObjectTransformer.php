<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Component\Core\Product\TaxedProductPriceCalculatorInterface;
use CoreShop\Component\Store\Model\StoreInterface;
use CoreShop\Component\Store\Repository\StoreRepositoryInterface;

class PriceObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @var TaxedProductPriceCalculatorInterface
     */
    private $productPriceCalculator;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    public function __construct(TaxedProductPriceCalculatorInterface $productPriceCalculator, StoreRepositoryInterface $storeRepository)
    {
        $this->productPriceCalculator = $productPriceCalculator;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @inheritDoc
     */
    public function transform($product, $item, $options = [])
    {
        $storeId = array_key_exists('store', $options) ? $options['store'] : null;

        if (empty($storeId)) {
            return [];
        }

        $store = $this->storeRepository->find($storeId);

        if (!$store instanceof StoreInterface) {
            return [];
        }

        $context = [
            'store' => $store,
            'currency' => $store->getCurrency()
        ];

        $item->setPrice($this->productPriceCalculator->getPrice($product, $context));

        return $item;
    }
}