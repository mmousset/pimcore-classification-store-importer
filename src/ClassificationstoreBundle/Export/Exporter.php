<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export;

use Divante\ClassificationstoreBundle\Component\CsvParser;
use Divante\ClassificationstoreBundle\Component\DataCleaner;
use Divante\ClassificationstoreBundle\Export\Interfaces\ItemFactoryInterface;
use Divante\ClassificationstoreBundle\Repository\CollectionRepository;
use Divante\ClassificationstoreBundle\Repository\GroupRepository;
use Divante\ClassificationstoreBundle\Repository\KeyRepository;
use Divante\ClassificationstoreBundle\Repository\StoreRepository;

/**
 * Class Exporter
 * @package Divante\ClassificationstoreBundle\Export
 */
class Exporter
{
    /**
     * @var StoreRepository
     */
    private $storeRepository;

    /**
     * @var CollectionRepository
     */
    private $collectionRepository;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var KeyRepository
     */
    private $keyRepository;

    /**
     * @var ItemFactoryInterface
     */
    private $itemFactory;

    /**
     * Exporter constructor.
     * @param StoreRepository      $storeRepository
     * @param CollectionRepository $collectionRepository
     * @param GroupRepository      $groupRepository
     * @param KeyRepository        $keyRepository
     * @param ItemFactoryInterface $itemFactory
     */
    public function __construct(
        StoreRepository      $storeRepository,
        CollectionRepository $collectionRepository,
        GroupRepository      $groupRepository,
        KeyRepository        $keyRepository,
        ItemFactoryInterface $itemFactory
    ) {
        $this->storeRepository      = $storeRepository;
        $this->collectionRepository = $collectionRepository;
        $this->groupRepository      = $groupRepository;
        $this->keyRepository        = $keyRepository;
        $this->itemFactory          = $itemFactory;
    }

    /**
     * @param string $delimiter
     * @param string $stores
     * @return string
     * @throws \Exception
     */
    public function getCsv(string $delimiter, string $storeName = ''): string
    {
        $storeId = 0;
        if ($storeName) {
            $storeId = $this->storeRepository->getByNameOrCreate($storeName)->getId();
        }

        $objects = array_merge(
            $this->storeRepository->getAll($storeId),
            $this->collectionRepository->getAll($storeId),
            $this->groupRepository->getAll($storeId),
            $this->keyRepository->getAll($storeId)
        );

        foreach ($objects as $object) {
            $data = $this->itemFactory->getItem($object)->getData();
            $data = DataCleaner::removeEmptyData($data);
            $array = CsvParser::arrayToCsv($data);
            $lines[] = implode($delimiter, $array);
        }
        $csv = implode(PHP_EOL, $lines);

        return $csv;
    }
}
