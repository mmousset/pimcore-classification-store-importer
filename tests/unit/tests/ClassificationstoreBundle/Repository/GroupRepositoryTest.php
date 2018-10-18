<?php
/**
 * @category    ClassificationstoreBundle
 * @date        17/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Repository;

use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

class GroupRepositoryTest extends CommonRepositoryTest
{
    public function test_create_group_and_verify_data()
    {
        $storeName = 'teststore';
        $groupName = 'testgroup';

        $group = $this->groupRepository->getByNameOrCreate($groupName, $storeName);
        $this->assertEquals($groupName, $group->getName());
        $this->assertEquals($storeName, $this->storeRepository->getById($group->getStoreId())->getName());
    }

    public function test_create_group()
    {
        $storeName = 'teststore';
        $groupName1 = 'testgroup1';

        $group1 = $this->groupRepository->getByNameOrCreate($groupName1, $storeName);
        $group2 = $this->groupRepository->getByNameOrCreate($groupName1, $storeName);
        $this->assertEquals($group1->getId(), $group2->getId());

        $group3 = $this->groupRepository->getById($group1->getId());
        $this->assertEquals($group2->getId(), $group3->getId());

        $this->assertGroupsCount(1);
        $this->assertStoresCount(1);

        $this->groupRepository->deleteAll();
        $this->storeRepository->deleteAll();

        $this->assertGroupsCount(0);
        $this->assertStoresCount(0);
    }

    public function test_create_groups()
    {
        $storeName1 = 'teststore1';
        $storeName2 = 'teststore2';
        $groupName1 = 'testgroup1';
        $groupName2 = 'testgroup2';

        $group_1_st_1 = $this->groupRepository->getByNameOrCreate($groupName1, $storeName1);
        $group_2_st_2 = $this->groupRepository->getByNameOrCreate($groupName2, $storeName1);
        $group_1_st_2 = $this->groupRepository->getByNameOrCreate($groupName1, $storeName2);
        $group_2_st_2 = $this->groupRepository->getByNameOrCreate($groupName2, $storeName2);

        $this->assertStoresCount(2);
        $this->assertGroupsCount(4);
    }

    public function test_add_key_to_group_twice()
    {
        $storeName = 'teststore';
        $groupName = 'testgroup';
        $keyName = 'testkey';

        $this->groupRepository->addKeyToGroup($keyName, $groupName, $storeName);
        $this->groupRepository->addKeyToGroup($keyName, $groupName, $storeName);

        $this->assertStoresCount(1);
        $this->assertGroupsCount(1);
        $this->assertKeysCount(1);
    }

    public function test_add_key_to_groups()
    {
        $storeName = 'teststore';
        $groupName = 'testgroup';
        $keyName1 = 'testkey1';
        $keyName2 = 'testkey2';

        $this->groupRepository->addKeyToGroup($keyName1, $groupName, $storeName);
        $this->groupRepository->addKeyToGroup($keyName2, $groupName, $storeName);

        $this->assertStoresCount(1);
        $this->assertGroupsCount(1);
        $this->assertKeysCount(2);
    }

    public function test_get_keys()
    {
        $storeName = 'teststore';
        $groupName = 'testgroup';
        $keyName1 = 'testkey1';
        $keyName2 = 'testkey2';

        $this->groupRepository->addKeyToGroup($keyName1, $groupName, $storeName);
        $this->groupRepository->addKeyToGroup($keyName2, $groupName, $storeName);

        $group = $this->groupRepository->getByNameOrCreate($groupName, $storeName);
        $keys = $this->groupRepository->getKeys($group->getId());
        foreach ($keys as $key) {
            $this->assertInstanceOf(KeyConfig::class, $key);
        }

        $this->assertStoresCount(1);
        $this->assertGroupsCount(1);
        $this->assertKeysCount(2);
    }
}
