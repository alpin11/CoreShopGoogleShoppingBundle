<?php


namespace CoreShop\Bundle\GoogleShoppingBundle\Model;

use CoreShop\Component\Inventory\Model\StockableInterface;
use CoreShop\Component\Product\Model\CategoryInterface;
use CoreShop\Component\Product\Model\ManufacturerInterface;
use Pimcore\Model\Asset\Image;

interface GoogleShoppingProductInterface extends StockableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $language
     * @return string
     */
    public function getName($language = null);

    /**
     * @param string $language
     * @return string
     */
    public function getDescription($language = null);

    /**
     * @return Image
     */
    public function getImage();

    /**
     * @return bool
     */
    public function isPreorder();

    /**
     * @return CategoryInterface[]
     */
    public function getCategories();

    /**
     * @return int
     */
    public function getGoogleProductCategory();

    /**
     * @return ManufacturerInterface
     */
    public function getManufacturer();

    /**
     * @return string
     */
    public function getEan();

    /**
     * @return string
     */
    public function getCondition();
}