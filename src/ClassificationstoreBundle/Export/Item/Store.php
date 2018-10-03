<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export\Item;

use Divante\ClassificationstoreBundle\Constants;
use Divante\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\DataObject\Classificationstore\StoreConfig;

/**
 * Class Store
 * @package Divante\ClassificationstoreBundle\Export\Item
 */
class Store extends AbstractItem implements ItemInterface
{
    /**
     * @var StoreConfig
     */
    private $storeConfig;

    /**
     * @param AbstractModel $model
     * @throws \Exception
     */
    public function setModel(AbstractModel $model)
    {
        if (!($model instanceof StoreConfig)) {
            throw new \Exception("Bad type, expected " . StoreConfig::class . " got: " . get_class($model));
        }

        $this->storeConfig = $model;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            Constants::ITEM        => Constants::ITEM_STORE,
            Constants::NAME        => $this->storeConfig->getName(),
            Constants::DESCRIPTION => $this->storeConfig->getDescription()
        ];

        return $data;
    }
}
