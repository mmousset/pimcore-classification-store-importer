<?php
/**
 * @category    ClassificationstoreBundle
 * @date        12/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Repository;

class StoreRepositoryTest extends CommonRepositoryTest
{
    public function test_empty_base_for_no_stores()
    {
        $this->assertStoresCount(0);
    }

    public function test_create_stores()
    {
        $name = 'teststore1';

        $store1 = $this->storeRepository->getByNameOrCreate($name);
        $store2 = $this->storeRepository->getByNameOrCreate($name);
        $this->assertEquals($store1->getId(), $store2->getId());

        $store3 = $this->storeRepository->getById($store1->getId());
        $this->assertEquals($store2->getId(), $store3->getId());

        $this->assertStoresCount(1);

        $this->storeRepository->deleteAll();

        $this->assertStoresCount(0);
    }
}
