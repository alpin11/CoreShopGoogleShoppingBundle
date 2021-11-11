<?php

namespace CoreShop\Bundle\GoogleShoppingBundle\Command;

use CoreShop\Bundle\GoogleShoppingBundle\DataProcessor\DataProcessorInterface;
use CoreShop\Bundle\GoogleShoppingBundle\Distributor\DistributorInterface;
use CoreShop\Component\Store\Repository\StoreRepositoryInterface;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Vitalybaev\GoogleMerchant\Feed;

class GoogleShoppingCommand extends AbstractCommand
{
    private DataProcessorInterface $dataProcessor;

    private DistributorInterface $distributor;

    private StoreRepositoryInterface $storeRepository;

    /**
     * @param \CoreShop\Bundle\GoogleShoppingBundle\DataProcessor\DataProcessorInterface $dataProcessor
     * @param \CoreShop\Bundle\GoogleShoppingBundle\Distributor\DistributorInterface $distributor
     * @param \CoreShop\Component\Store\Repository\StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        DataProcessorInterface $dataProcessor,
        DistributorInterface $distributor,
        StoreRepositoryInterface $storeRepository
    ) {
        parent::__construct();

        $this->dataProcessor = $dataProcessor;
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
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = json_decode($input->getOption('params'), true);

        $path = sprintf("%s/google-shopping/%s", $params['base_url'], $params['filename']);
        $params['file_url'] = $path;

        $store = $this->storeRepository->find($params['store']);
        $feed = new Feed($store->getName(), $params['file_url'], null);

        $logger = new ConsoleLogger($output);
        $this->dataProcessor->setLogger($logger);
        $this->dataProcessor->runProcess($feed, $params);
        $this->distributor->distribute($feed, $params);

        $output->writeln('Google Shopping feed successfully generated');
        $output->writeln('The feed is stored under: ' . $path);

        return 0;
    }
}
