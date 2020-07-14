<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Command;


use CoreShop\Bundle\GoogleShoppingBundle\DataCollector\DataCollectorInterface;
use CoreShop\Bundle\GoogleShoppingBundle\Distributor\DistributorInterface;
use CoreShop\Bundle\GoogleShoppingBundle\ObjectTransformer\ObjectTransformerInterface;
use Pimcore\Console\AbstractCommand;
use Pimcore\Tool;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function __construct(
        DataCollectorInterface $dataCollector,
        ObjectTransformerInterface $objectTransformer,
        DistributorInterface $distributor
    )
    {
        $this->dataCollector = $dataCollector;
        $this->objectTransformer = $objectTransformer;
        $this->distributor = $distributor;

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

        $result = $this->dataCollector->collect($params);
        $data = $this->objectTransformer->transform($result, null, $params);
        $this->distributor->distribute($data, $params);

        $path = sprintf("%s/google-shopping/%s", Tool::getHostUrl(), $params['filename']);

        $output->writeln('Google Shopping feed successfully generated');
        $output->writeln('The feed is stored under: ' . $path);
    }
}