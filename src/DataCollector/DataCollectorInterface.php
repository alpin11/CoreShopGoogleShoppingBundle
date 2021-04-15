<?php

namespace CoreShop\Bundle\GoogleShoppingBundle\DataCollector;

use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;

interface DataCollectorInterface
{
    /**
     * @param array $options
     * @return GoogleShoppingProductInterface[]
     */
    public function collect($options = []): array;
}