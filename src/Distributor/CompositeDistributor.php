<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Distributor;


use Zend\Stdlib\PriorityQueue;

class CompositeDistributor implements DistributorInterface
{
    /**
     * @var PriorityQueue|DistributorInterface[]
     */
    private $distributors;

    public function __construct()
    {
        $this->distributors = new PriorityQueue();
    }

    /**
     * @param DistributorInterface $distributor
     * @param int $priority
     */
    public function addDistributor(DistributorInterface $distributor, $priority = 0)
    {
        $this->distributors->insert($distributor, $priority);
    }

    /**
     * @inheritDoc
     */
    public function distribute($feed, $options = [])
    {
        foreach ($this->distributors as $distributor) {
            $distributor->distribute($feed, $options);
        }
    }
}