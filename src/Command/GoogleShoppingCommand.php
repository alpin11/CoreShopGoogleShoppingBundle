<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Command;


use CoreShop\Bundle\GoogleShoppingBundle\DataCollector\DataCollectorInterface;
use CoreShop\Bundle\GoogleShoppingBundle\Distributor\DistributorInterface;
use CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer\ObjectTransformerInterface;
use CoreShop\Component\Store\Repository\StoreRepositoryInterface;
use Pimcore\Console\AbstractCommand;
use Pimcore\Tool;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;

class GoogleShoppingCommand extends AbstractCommand
{
    /**
     * @var DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @var ObjectTransformerInterface
     */
    private $objectTransformer;

    /**
     * @var DistributorInterface
     */
    private $distributor;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    public function __construct(
        DataCollectorInterface $dataCollector,
        ObjectTransformerInterface $objectTransformer,
        DistributorInterface $distributor,
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->dataCollector = $dataCollector;
        $this->objectTransformer = $objectTransformer;
        $this->distributor = $distributor;
        $this->storeRepository = $storeRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('coreshop:google-shopping:dump-feed')
            ->setDescription('Generate Google Shopping feed')
            ->addOption(
                'params',
                'p',
                InputOption::VALUE_REQUIRED
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = json_decode($input->getOption('params'), true);
        $path = sprintf("%s/google-shopping/%s", $params['base_url'], $params['filename']);

        $params['file_url'] = $path;

        $result = $this->dataCollector->collect($params);

        $store = $this->storeRepository->find($params['store']);
        $feed = new Feed($store->getName(), $params['file_url'], null);

        foreach ($result as $item) {
            $entry = new Product();
            $entry = $this->objectTransformer->transform($item, $entry, $params);

            $feed->addProduct($entry);
        }

        $this->distributor->distribute($feed, $params);

        $output->writeln('Google Shopping feed successfully generated');
        $output->writeln('The feed is stored under: ' . $path);
    }
}