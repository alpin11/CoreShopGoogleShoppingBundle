<?php

namespace CoreShop\Bundle\GoogleShoppingBundle\DataProcessor;

use CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter\ObjectFilterInterface;
use CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer\ObjectTransformerInterface;
use CoreShop\Component\Pimcore\BatchProcessing\BatchListing;
use CoreShop\Component\Product\Repository\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;
use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;
use Zend\Stdlib\PriorityQueue;

class DefaultDataProcessor implements DataProcessorInterface
{
    public const BATCH_SIZE = 100;

    /**
     * @var PriorityQueue|ProductRepositoryInterface[]
     */
    private $repositories;

    private LoggerInterface $logger;

    private ObjectFilterInterface $objectFilter;

    private ObjectTransformerInterface $objectTransformer;

    /**
     * @param \CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter\ObjectFilterInterface $objectFilter
     * @param \CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer\ObjectTransformerInterface $objectTransformer
     */
    public function __construct(
        ObjectFilterInterface $objectFilter,
        ObjectTransformerInterface $objectTransformer
    ) {
        $this->repositories = new PriorityQueue();
        $this->objectFilter = $objectFilter;
        $this->objectTransformer = $objectTransformer;
    }

    /**
     * @param ProductRepositoryInterface $repository
     * @param int $priority
     */
    public function addRepository(ProductRepositoryInterface $repository, int $priority = 0)
    {
        $this->repositories->insert($repository, $priority);
    }

    /**
     * @inheritDoc
     */
    public function runProcess(Feed $feed, array $options = []): Feed
    {
        foreach ($this->repositories as $repository) {
            $list = $repository->getList();
            $list->addConditionParam('active = 1');
            $this->objectFilter->apply($list, $options);

            $totalCount = $list->getTotalCount();
            if ($totalCount > 0) {
                $totalBatches = (int)ceil($totalCount / self::BATCH_SIZE);
                $this->getLogger()->notice('Started processing ' . get_class($repository) . ' Total batches: ' . $totalBatches . ' Batch size: ' . self::BATCH_SIZE . ' Total objects: ' . $totalCount);
                $batchListing = new BatchListing($list, self::BATCH_SIZE);
                $this->processListBatch($feed, $batchListing, $totalBatches, $options);
            } else {
                $this->getLogger()->notice('There is nothing to be processed');
            }
        }

        return $feed;
    }

    /**
     * @param \Vitalybaev\GoogleMerchant\Feed $feed
     * @param \CoreShop\Component\Pimcore\BatchProcessing\BatchListing $batchListing
     * @param int $totalBatches
     * @param array $options
     */
    private function processListBatch(
        Feed $feed,
        BatchListing $batchListing,
        int $totalBatches,
        array $options = []
    ): void {
        $objectIteration = 0;
        $batchIteration = 1;
        foreach ($batchListing as $object) {
            $startMicroTime = microtime(true);
            $entry = new Product();
            $entry = $this->objectTransformer->transform($object, $entry, $options);

            $feed->addProduct($entry);
            $objectIteration++;

            $message = 'Batch: (' . $batchIteration . '/' . $totalBatches . ') - '
                . $objectIteration . ' / ' . self::BATCH_SIZE
                . ' Added Object ID: ' . $object->getId()
                . ' Time: ' . round(microtime(true) - $startMicroTime, 3) . 's';

            if ($objectIteration % self::BATCH_SIZE === 0) {
                $message .= ' MEMORY: ' . round(memory_get_usage()/1048576,2) . ' MB';
                $objectIteration = 0;
                $batchIteration++;
            }

            $this->getLogger()->notice($message);
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
