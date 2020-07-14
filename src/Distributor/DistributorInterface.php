<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Distributor;


interface DistributorInterface
{
    /**
     * @param $feed
     * @param array $options
     * @return array
     */
    public function distribute($feed, $options = []);
}