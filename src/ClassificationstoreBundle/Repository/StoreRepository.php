<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Repository;

use Divante\ClassificationstoreBundle\Export\Item\Store;
use Pimcore\Model\DataObject\Classificationstore\StoreConfig;

/**
 * Class StoreRepository
 * @package Divante\ClassificationstoreBundle\Repository
 */
class StoreRepository
{
    /**
     * @param string $name
     * @return StoreConfig
     */
    public function getByNameOrCreate(string $name): StoreConfig
    {
        $storeConfig = StoreConfig::getByName($name);
        if (!$storeConfig) {
            $storeConfig = new StoreConfig();
            $storeConfig->setName($name);
            $storeConfig->save();
        }

        return $storeConfig;
    }

    /**
     * @param int $storeId
     * @return StoreConfig
     */
    public function getById(int $storeId): StoreConfig
    {
        return StoreConfig::getById($storeId);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $list = new StoreConfig\Listing();
        return $list->load();
    }
}
