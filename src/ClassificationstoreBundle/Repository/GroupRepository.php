<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Repository;

use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyGroupRelation;
use Pimcore\Model\DataObject\Classificationstore\KeyGroupRelation\Listing as KeyGroupRelationListing;

/**
 * Class GroupRepository
 * @package Divante\ClassificationstoreBundle\Repository
 */
class GroupRepository
{
    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @var KeyRepository
     */
    private $keyRepository;

    /**
     * GroupRepository constructor.
     * @param StoreRepository $storeRepository
     * @param KeyRepository $keyRepository
     */
    public function __construct(StoreRepository $storeRepository, KeyRepository $keyRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->keyRepository   = $keyRepository;
    }

    /**
     * @param string $name
     * @param string $storeName
     * @return GroupConfig
     */
    public function getByNameOrCreate(string $name, string $storeName): GroupConfig
    {
        $storeConfig = $this->storeRepository->getByNameOrCreate($storeName);
        $groupConfig = GroupConfig::getByName($name, $storeConfig->getId());
        if (!$groupConfig) {
            $groupConfig = new GroupConfig();
            $groupConfig->setStoreId($storeConfig->getId());
            $groupConfig->setName($name);
            $groupConfig->save();
        }

        return $groupConfig;
    }

    /**
     * @param string $keyName
     * @param string $groupName
     * @param string $storeName
     */
    public function addKeyToGroup(string $keyName, string $groupName, string $storeName)
    {
        $groupConfig = $this->getByNameOrCreate($groupName, $storeName);
        $keyConfig = $this->keyRepository->getByNameOrCreate($keyName, $storeName);

        $groupId = $groupConfig->getId();
        $keyId = $keyConfig->getId();

        $list = new KeyGroupRelationListing();
        $list->setCondition('keyId = ? AND groupId = ?', [$keyId, $groupId]);
        $list->load();
        if (count($list->getList()) > 0) {
            return;
        }

        $relation = new KeyGroupRelation();
        $relation->setGroupId($groupId);
        $relation->setKeyId($keyId);

        $relation->save();
    }

    /**
     * @param int $groupId
     * @return KeyConfig[]
     */
    public function getKeys(int $groupId): array
    {
        $keys = [];
        $list = new KeyGroupRelationListing();
        $list->setCondition('groupId = ?', [$groupId]);
        $list->load();
        /** @var KeyGroupRelation $relation */
        foreach ($list->getList() as $relation) {
            $keyId = $relation->getKeyId();
            $keys[] = $this->keyRepository->getById($keyId);
        }

        return $keys;
    }

    /**
     * @param int $groupId
     * @return GroupConfig
     */
    public function getById(int $groupId): GroupConfig
    {
        return GroupConfig::getById($groupId);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $list = new GroupConfig\Listing();
        return $list->load();
    }

    /**
     *
     */
    public function deleteAll()
    {
        foreach ($this->getAll() as $groupConfig) {
            $groupConfig->delete();
        }
    }
}
