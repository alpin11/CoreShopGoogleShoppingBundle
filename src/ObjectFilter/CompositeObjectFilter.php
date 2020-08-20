<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter;


use Pimcore\Model\Listing\AbstractListing;
use Zend\Stdlib\PriorityQueue;

class CompositeObjectFilter implements ObjectFilterInterface
{
    /**
     * @var PriorityQueue|ObjectFilterInterface[]
     */
    private $filters;

    public function __construct()
    {
        $this->filters = new PriorityQueue();
    }

    /**
     * @param ObjectFilterInterface $filter
     * @param int $priority
     */
    public function addFilter(ObjectFilterInterface $filter, $priority = 0)
    {
        $this->filters->insert($filter, $priority);
    }

    /**
     * @inheritDoc
     */
    public function apply(AbstractListing $listing, $options = [])
    {
        foreach ($this->filters as $filter) {
            $filter->apply($listing, $options);
        }
    }
}