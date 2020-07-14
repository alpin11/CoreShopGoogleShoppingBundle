<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter;


use Pimcore\Model\Listing\AbstractListing;

class StoresObjectFilter implements ObjectFilterInterface
{
    /**
     * @inheritDoc
     */
    public function apply(AbstractListing $listing, $options = [])
    {
        $store = array_key_exists('store', $options) ? $options['store'] : null;

        if (empty($store)) {
            return;
        }

        $listing->addConditionParam('stores LIKE "%' . $store . '%"');
    }
}