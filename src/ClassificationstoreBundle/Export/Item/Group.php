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
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class Group
 * @package Mousset\ClassificationstoreBundle\Export\Item
 */
class Group extends AbstractItem implements ItemInterface
{
    /**
     * @var GroupConfig
     */
    private $groupConfig;

    /**
     * @param AbstractModel $model
     * @throws \Exception
     */
    public function setModel(AbstractModel $model)
    {
        if (!($model instanceof GroupConfig)) {
            throw new \Exception("Bad type, expected " . GroupConfig::class . " got: " . get_class($model));
        }

        $this->groupConfig = $model;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $storeName = $this->storeRepository->getById($this->groupConfig->getStoreId())->getName();

        $keyList = $this->groupRepository->getKeys($this->groupConfig->getId());
        $keysNames = [];
        $keysSorter = [];
        /** @var KeyConfig $keyConfig */
        foreach ($keyList as $keyConfig) {
            $keysNames[] = $keyConfig['object']->getName();
            $keysSorter[] = $keyConfig['sorter'];
        }
        $keys = implode(Constants::DELIMITER, $keysNames);
        $sorters = implode(Constants::DELIMITER, $keysSorter);

        $data = [
            Constants::ITEM        => Constants::ITEM_GROUP,
            Constants::NAME        => $this->groupConfig->getName(),
            Constants::DESCRIPTION => $this->groupConfig->getDescription(),
            Constants::STORE       => $storeName,
            Constants::KEYS        => $keys,
            Constants::SORTER      => $sorters
        ];

        return $data;
    }
}
