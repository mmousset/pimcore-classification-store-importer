<?php
/**
 * @category    ClassificationstoreBundle
 * @date        02/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Export\Item;

use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\DataObject\Classificationstore\CollectionConfig;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;

/**
 * Class Collection
 * @package Mousset\ClassificationstoreBundle\Export\Item
 */
class Collection extends AbstractItem implements ItemInterface
{
    /**
     * @var CollectionConfig
     */
    private $collectionConfig;

    /**
     * @param AbstractModel $model
     * @throws \Exception
     */
    public function setModel(AbstractModel $model)
    {
        if (!($model instanceof CollectionConfig)) {
            throw new \Exception("Bad type, expected " . CollectionConfig::class . " got: " . get_class($model));
        }

        $this->collectionConfig = $model;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $storeName = $this->storeRepository->getById($this->collectionConfig->getStoreId())->getName();

        $groupList = $this->collectionRepository->getGroups($this->collectionConfig->getId());
        $groupsNames = [];
        /** @var GroupConfig $groupConfig */
        foreach ($groupList as $groupConfig) {
            $groupsNames[] = $groupConfig->getName();
        }
        $groups = implode(Constants::DELIMITER, $groupsNames);

        $data = [
            Constants::ITEM        => Constants::ITEM_COLLECTION,
            Constants::NAME        => $this->collectionConfig->getName(),
            Constants::DESCRIPTION => $this->collectionConfig->getDescription(),
            Constants::STORE       => $storeName,
            Constants::GROUPS      => $groups
        ];

        return $data;
    }
}
