<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Repository;

use Pimcore\Model\DataObject\Classificationstore\StoreConfig;

/**
 * Class StoreRepository
 * @package Divante\ClassificationstoreBundle\Repository
 */
class StoreRepository
{
    /**
     * @param string $storeName
     * @return StoreConfig
     */
    public function getByNameOrCreate(string $storeName): StoreConfig
    {
        $storeConfig = StoreConfig::getByName($storeName);
        if (!$storeConfig) {
            $storeConfig = new StoreConfig();
            $storeConfig->setName($storeName);
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
     * @param int $storeId
     * @return StoreConfig[]
     */
    public function getAll(int $storeId = 0): array
    {
        $list = new StoreConfig\Listing();
        if ($storeId) {
            $list->setCondition('id = ?', [$storeId]);
        }
        return $list->load();
    }

    /**
     *
     */
    public function deleteAll(): void
    {
        foreach ($this->getAll() as $storeConfig) {
            $storeConfig->delete();
        }
    }
}
