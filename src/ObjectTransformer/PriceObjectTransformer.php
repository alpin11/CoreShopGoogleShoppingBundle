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

    /**
     * @var float
     */
    private $decimalFactor;

    public function __construct(
        TaxedProductPriceCalculatorInterface $productPriceCalculator,
        StoreRepositoryInterface $storeRepository,
        float $decimalFactor
    )
    {
        $this->productPriceCalculator = $productPriceCalculator;
        $this->storeRepository = $storeRepository;
        $this->decimalFactor = $decimalFactor;
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

        $price = sprintf("%01.2f", ($this->productPriceCalculator->getPrice($product, $context) / $this->decimalFactor));;

        $item->setPrice(sprintf('%s %s', $price, $store->getCurrency()->getIsoCode()));

        return $item;
    }
}