<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DataCollector;


interface DataCollectorInterface
{
    /**
     * @param array $options
     * @return GoogleShoppingProductInterface[]
     */
    public function collect($options = []);
}