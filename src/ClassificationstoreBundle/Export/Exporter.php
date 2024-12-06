<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Export;

use Mousset\ClassificationstoreBundle\Component\CsvParser;
use Mousset\ClassificationstoreBundle\Component\DataCleaner;
use Mousset\ClassificationstoreBundle\Export\Interfaces\ItemFactoryInterface;
use Mousset\ClassificationstoreBundle\Repository\CollectionRepository;
use Mousset\ClassificationstoreBundle\Repository\GroupRepository;
use Mousset\ClassificationstoreBundle\Repository\KeyRepository;
use Mousset\ClassificationstoreBundle\Repository\StoreRepository;

/**
 * Class Exporter
 * @package Mousset\ClassificationstoreBundle\Export
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
     * @param string $enclosure
     * @param string $stores
     * @return string
     * @throws \Exception
     */
    public function getCsv(string $delimiter, string $enclosure, string $storeName = ''): string
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
            $array = CsvParser::arrayToCsv($data, $enclosure);
            $lines[] = implode($delimiter, $array);
        }
        $csv = implode(PHP_EOL, $lines);

        return $csv;
    }
}
