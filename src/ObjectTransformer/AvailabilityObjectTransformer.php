<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Vitalybaev\GoogleMerchant\Product\Availability\Availability;

class AvailabilityObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @var AvailabilityCheckerInterface
     */
    private $availabilityChecker;

    public function __construct(AvailabilityCheckerInterface $availabilityChecker)
    {
        $this->availabilityChecker = $availabilityChecker;
    }

    /**
     * @inheritDoc
     */
    public function transform($product, $item, $options = [])
    {
        if ($product->isPreorder()) {
            $item->setAvailability(Availability::PREORDER);
        } else if ($this->availabilityChecker->isStockAvailable($product)) {
            $item->setAvailability(Availability::IN_STOCK);
        } else {
            $item->setAvailability(Availability::OUT_OF_STOCK);
        }

        return $item;
    }
}