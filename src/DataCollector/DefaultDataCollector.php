<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\DataCollector;


use CoreShop\Bundle\GoogleShoppingBundle\ObjectFilter\ObjectFilterInterface;
use CoreShop\Component\Product\Repository\ProductRepositoryInterface;
use Zend\Stdlib\PriorityQueue;

class DefaultDataCollector implements DataCollectorInterface
{

    /**
     * @var PriorityQueue|ProductRepositoryInterface[]
     */
    private $repositories;

    /**
     * @var ObjectFilterInterface
     */
    private $objectFilter;

    public function __construct(ObjectFilterInterface $objectFilter)
    {
        $this->repositories = new PriorityQueue();
        $this->objectFilter = $objectFilter;
    }

    /**
     * @param ProductRepositoryInterface $repository
     * @param int $priority
     */
    public function addRepository(ProductRepositoryInterface $repository, $priority = 0)
    {
        $this->repositories->insert($repository, $priority);
    }

    /**
     * @inheritDoc
     */
    public function collect($options = [])
    {
        $objects = [];

        foreach ($this->repositories as $repository) {
            $list = $repository->getList();

            $this->objectFilter->apply($list, $options);

            $currentObjects = $list->getObjects();

            $objects = array_merge($currentObjects, $objects);
        }

        return $objects;
    }
}