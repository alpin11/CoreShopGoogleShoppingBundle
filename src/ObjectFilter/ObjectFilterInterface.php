<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter;


use Pimcore\Model\Listing\AbstractListing;

interface ObjectFilterInterface
{
    /**
     * @param AbstractListing $listing
     * @param array $options
     * @return mixed
     */
    public function apply(AbstractListing $listing, $options = []);
}