<?php
/**
 * @category    ClassificationstoreBundle
 * @date        17/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Repository;

use Pimcore\Model\DataObject\Classificationstore\GroupConfig;

class CollectionRepositoryTest extends CommonRepositoryTest
{
    public function test_create_collection_and_verify_data()
    {
        $storeName = 'teststore';
        $collectionName = 'testcol';

        $col = $this->collectionRepository->getByNameOrCreate($collectionName, $storeName);
        $this->assertEquals($collectionName, $col->getName());
        $this->assertEquals($storeName, $this->storeRepository->getById($col->getStoreId())->getName());
    }

    public function test_create_collection()
    {
        $storeName = 'teststore';
        $collectionName1 = 'testcol1';

        $col1 = $this->collectionRepository->getByNameOrCreate($collectionName1, $storeName);
        $col2 = $this->collectionRepository->getByNameOrCreate($collectionName1, $storeName);
        $this->assertEquals($col1->getId(), $col2->getId());

        $col3 = $this->collectionRepository->getById($col1->getId());
        $this->assertEquals($col2->getId(), $col3->getId());

        $this->assertCollectionsCount(1);
        $this->assertStoresCount(1);

        $this->collectionRepository->deleteAll();
        $this->storeRepository->deleteAll();

        $this->assertCollectionsCount(0);
        $this->assertStoresCount(0);
    }

    public function test_create_collections()
    {
        $storeName1 = 'teststore1';
        $storeName2 = 'teststore2';
        $collectionName1 = 'testcol1';
        $collectionName2 = 'testcol2';

        $col_1_st_1 = $this->collectionRepository->getByNameOrCreate($collectionName1, $storeName1);
        $col_2_st_2 = $this->collectionRepository->getByNameOrCreate($collectionName2, $storeName1);
        $col_1_st_2 = $this->collectionRepository->getByNameOrCreate($collectionName1, $storeName2);
        $col_2_st_2 = $this->collectionRepository->getByNameOrCreate($collectionName2, $storeName2);

        $this->assertStoresCount(2);
        $this->assertCollectionsCount(4);
    }

    public function test_add_group_to_collections_twice()
    {
        $storeName = 'teststore';
        $collectionName = 'testcol';
        $groupName = 'testgroup';

        $this->collectionRepository->addGroupToCollection($groupName, $collectionName, $storeName);
        $this->collectionRepository->addGroupToCollection($groupName, $collectionName, $storeName);

        $this->assertStoresCount(1);
        $this->assertCollectionsCount(1);
        $this->assertGroupsCount(1);
    }

    public function test_add_group_to_collections()
    {
        $storeName = 'teststore';
        $collectionName = 'testcol';
        $groupName1 = 'testgroup1';
        $groupName2 = 'testgroup2';

        $this->collectionRepository->addGroupToCollection($groupName1, $collectionName, $storeName);
        $this->collectionRepository->addGroupToCollection($groupName2, $collectionName, $storeName);

        $this->assertStoresCount(1);
        $this->assertCollectionsCount(1);
        $this->assertGroupsCount(2);
    }

    public function test_get_groups()
    {
        $storeName = 'teststore';
        $collectionName = 'testcol';
        $groupName1 = 'testgroup1';
        $groupName2 = 'testgroup2';

        $this->collectionRepository->addGroupToCollection($groupName1, $collectionName, $storeName);
        $this->collectionRepository->addGroupToCollection($groupName2, $collectionName, $storeName);

        $col = $this->collectionRepository->getByNameOrCreate($collectionName, $storeName);
        $groups = $this->collectionRepository->getGroups($col->getId());
        foreach ($groups as $group) {
            $this->assertInstanceOf(GroupConfig::class, $group);
        }

        $this->assertStoresCount(1);
        $this->assertCollectionsCount(1);
        $this->assertGroupsCount(2);
    }
}
