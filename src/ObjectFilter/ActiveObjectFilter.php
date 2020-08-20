<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter;


use Pimcore\Model\Listing\AbstractListing;

class ActiveObjectFilter implements ObjectFilterInterface
{
    /**
     * @inheritDoc
     */
    public function apply(AbstractListing $listing, $options = [])
    {
        $active = array_key_exists('active', $options) ? $options['active'] : null;

        if (empty($active)) {
            return;
        }

        $listing->addConditionParam('active = ?', $active);
    }
}