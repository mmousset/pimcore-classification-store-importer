<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Repository;

use Pimcore\Model\DataObject\Classificationstore\CollectionConfig;
use Pimcore\Model\DataObject\Classificationstore\CollectionGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\CollectionGroupRelation\Listing as CollectionGroupRelationListing;

/**
 * Class CollectionRepository
 * @package Divante\ClassificationstoreBundle\Repository
 */
class CollectionRepository
{
    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * CollectionRepository constructor.
     * @param StoreRepository $storeRepository
     * @param GroupRepository $groupRepository
     */
    public function __construct(StoreRepository $storeRepository, GroupRepository $groupRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * @param string $name
     * @param string $storeName
     * @return CollectionConfig
     */
    public function getByNameOrCreate(string $name, string $storeName): CollectionConfig
    {
        $storeConfig = $this->storeRepository->getByNameOrCreate($storeName);
        $collectionConfig = CollectionConfig::getByName($name, $storeConfig->getId());
        if (!$collectionConfig) {
            $collectionConfig = new CollectionConfig();
            $collectionConfig->setStoreId($storeConfig->getId());
            $collectionConfig->setName($name);
            $collectionConfig->save();
        }

        return $collectionConfig;
    }

    /**
     * @param string $groupName
     * @param string $collectionName
     * @param string $storeName
     */
    public function addGroupToCollection(string $groupName, string $collectionName, string $storeName)
    {
        $collectionConfig = $this->getByNameOrCreate($collectionName, $storeName);
        $groupConfig = $this->groupRepository->getByNameOrCreate($groupName, $storeName);

        $colId = $collectionConfig->getId();
        $groupId = $groupConfig->getId();

        $list = new CollectionGroupRelationListing();
        $list->setCondition('colId = ? AND groupId = ?', [$colId, $groupId]);
        if (count($list->getList()) > 0) {
            return;
        }

        $relation = new CollectionGroupRelation();
        $relation->setColId($colId);
        $relation->setGroupId($groupId);

        $relation->save();
    }
}
