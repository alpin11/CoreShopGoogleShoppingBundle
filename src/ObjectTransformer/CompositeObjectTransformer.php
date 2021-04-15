<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


use CoreShop\Bundle\GoogleShoppingBundle\Model\GoogleShoppingProductInterface;
use CoreShop\Component\Store\Repository\StoreRepositoryInterface;
use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;
use Webmozart\Assert\Assert;
use Zend\Stdlib\PriorityQueue;

class CompositeObjectTransformer implements ObjectTransformerInterface
{
    /**
     * @var PriorityQueue|ObjectTransformerInterface[]
     */
    private $transformers;

    public function __construct()
    {
        $this->transformers = new PriorityQueue();
    }

    /**
     * @param ObjectTransformerInterface $transformer
     * @param int $priority
     */
    public function addTransformer(ObjectTransformerInterface $transformer, $priority = 0)
    {
        $this->transformers->insert($transformer, $priority);
    }

    /**
     * @inheritDoc
     */
    public function transform(GoogleShoppingProductInterface $product, Product $item, array $options = [])
    {
        $item = new Product();

        foreach ($this->transformers as $transformer) {
            $transformer->transform($product, $item, $options);
        }

        return $item;
    }

}