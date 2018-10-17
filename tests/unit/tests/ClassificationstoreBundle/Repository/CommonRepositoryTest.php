<?php
/**
 * @category    ClassificationstoreBundle
 * @date        17/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Repository;

use Divante\ClassificationstoreBundle\Repository\CollectionRepository;
use Divante\ClassificationstoreBundle\Repository\GroupRepository;
use Divante\ClassificationstoreBundle\Repository\KeyRepository;
use Divante\ClassificationstoreBundle\Repository\StoreRepository;
use Pimcore\Test\KernelTestCase;

abstract class CommonRepositoryTest extends KernelTestCase
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @var KeyRepository
     */
    protected $keyRepository;

    protected function setUp()
    {
        self::bootKernel();

        $this->storeRepository = static::$kernel
            ->getContainer()
            ->get(StoreRepository::class);

        $this->collectionRepository = static::$kernel
            ->getContainer()
            ->get(CollectionRepository::class);

        $this->groupRepository = static::$kernel
            ->getContainer()
            ->get(GroupRepository::class);

        $this->keyRepository = static::$kernel
            ->getContainer()
            ->get(KeyRepository::class);

        $this->assertStoresCount(0);
        $this->assertCollectionsCount(0);
        $this->assertGroupsCount(0);
        $this->assertKeysCount(0);
    }

    protected function tearDown()
    {
        $this->collectionRepository->deleteAll();
        $this->groupRepository->deleteAll();
        $this->keyRepository->deleteAll();
        $this->storeRepository->deleteAll();
    }

    protected function assertStoresCount(int $count)
    {
        $items = $this->storeRepository->getAll();
        $this->assertEquals($count, count($items));
    }

    protected function assertCollectionsCount(int $count)
    {
        $items = $this->collectionRepository->getAll();
        $this->assertEquals($count, count($items));
    }

    protected function assertGroupsCount(int $count)
    {
        $items = $this->groupRepository->getAll();
        $this->assertEquals($count, count($items));
    }

    protected function assertKeysCount(int $count)
    {
        $items = $this->keyRepository->getAll();
        $this->assertEquals($count, count($items));
    }
}
