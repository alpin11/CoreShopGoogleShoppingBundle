<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Distributor;


use Exception;
use Pimcore\Logger;
use Symfony\Component\Filesystem\Filesystem;

class PublicStorageDistributor implements DistributorInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $storageFolder;

    public function __construct(
        Filesystem $filesystem,
        string $storageFolder
    )
    {
        $this->filesystem = $filesystem;
        $this->storageFolder = $storageFolder;
    }

    /**
     * @inheritDoc
     */
    public function distribute($feed, $options = [])
    {
        if (empty($feed)) {
            return;
        }

        if (empty($options['filename'])) {
            return;
        }

        $feedXml = $feed->build();
        $filename = sprintf("%s/google-shopping/%s", $this->storageFolder, $options['filename']);

        try {
            if ($this->filesystem->exists($filename)) {
                $this->filesystem->remove($filename);
            }

            $this->filesystem->dumpFile($filename, $feedXml);
        } catch (Exception $e) {
            Logger::log($e->getMessage());
        }
    }
}