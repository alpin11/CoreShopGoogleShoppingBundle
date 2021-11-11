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
    public const BATCH_SIZE = 10;

    private DataCollectorInterface $dataCollector;

    private ObjectTransformerInterface $objectTransformer;

    private DistributorInterface $distributor;

    private StoreRepositoryInterface $storeRepository;

    /**
     * @param \CoreShop\Bundle\GoogleShoppingBundle\DataCollector\DataCollectorInterface $dataCollector
     * @param \CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer\ObjectTransformerInterface $objectTransformer
     * @param \CoreShop\Bundle\GoogleShoppingBundle\Distributor\DistributorInterface $distributor
     * @param \CoreShop\Component\Store\Repository\StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        DataCollectorInterface $dataCollector,
        ObjectTransformerInterface $objectTransformer,
        DistributorInterface $distributor,
        StoreRepositoryInterface $storeRepository
    ) {
        parent::__construct();

        $this->dataCollector = $dataCollector;
        $this->objectTransformer = $objectTransformer;
        $this->distributor = $distributor;
        $this->storeRepository = $storeRepository;
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

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = json_decode($input->getOption('params'), true);
        $path = sprintf("%s/google-shopping/%s", $params['base_url'], $params['filename']);

        $params['file_url'] = $path;

        $result = $this->dataCollector->collect($params);

        $store = $this->storeRepository->find($params['store']);
        $feed = new Feed($store->getName(), $params['file_url'], null);

        $iteration = 0;
        $count = count($result);
        foreach ($result as $item) {
            $startMicroTime = microtime(true);
            $entry = new Product();
            $entry = $this->objectTransformer->transform($item, $entry, $params);

            $feed->addProduct($entry);

            $message = '(' . $iteration++ . '/' . $count . ')'
                . ' Added product ID: ' . $item->getId()
                . ' Time: ' . round(microtime(true) - $startMicroTime, 3) . 's';

            if ($iteration % self::BATCH_SIZE === 0) {
                $message .= ' MEMORY: ' . round(memory_get_usage()/1048576,2) . ' MB';
            }

            $output->writeln($message);

            if ($iteration === 1000) {
                break;
            }
        }

        $this->distributor->distribute($feed, $params);

        $output->writeln('Google Shopping feed successfully generated');
        $output->writeln('The feed is stored under: ' . $path);
    }
}
