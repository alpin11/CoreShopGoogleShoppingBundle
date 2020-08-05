<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer;


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

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->transformers = new PriorityQueue();
        $this->storeRepository = $storeRepository;
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
    public function transform($product, $item, $options = [])
    {
        Assert::isArray($product);

        $store = $this->storeRepository->find($options['store']);

        $feed = new Feed($store->getName(), $options['file_url'], null);

        foreach ($product as $productItem) {
            $item = new Product();

            foreach ($this->transformers as $transformer) {
                $transformer->transform($productItem, $item, $options);
            }

            $feed->addProduct($item);
        }

        return $feed;
    }

}