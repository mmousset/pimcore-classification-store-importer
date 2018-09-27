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
}
