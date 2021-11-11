<?php

namespace CoreShop\Bundle\GoogleShoppingBundle\DataProcessor;

use Psr\Log\LoggerInterface;
use Vitalybaev\GoogleMerchant\Feed;

interface DataProcessorInterface
{
    /**
     * @param \Vitalybaev\GoogleMerchant\Feed $feed
     * @param array $options
     *
     * @return Feed
     */
    public function runProcess(Feed $feed, array $options = []): Feed;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void;

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface;
}
