<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export\Item;

use Divante\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Divante\ClassificationstoreBundle\Repository\CollectionRepository;
use Divante\ClassificationstoreBundle\Repository\GroupRepository;
use Divante\ClassificationstoreBundle\Repository\KeyRepository;
use Divante\ClassificationstoreBundle\Repository\StoreRepository;

/**
 * Class AbstractItem
 * @package Divante\ClassificationstoreBundle\Export\Item
 */
abstract class AbstractItem implements ItemInterface
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

    /**
     * AbstractItem constructor.
     * @param StoreRepository $storeRepository
     * @param CollectionRepository $collectionRepository
     * @param GroupRepository $groupRepository
     * @param KeyRepository $keyRepository
     */
    public function __construct(
        StoreRepository      $storeRepository,
        CollectionRepository $collectionRepository,
        GroupRepository      $groupRepository,
        KeyRepository        $keyRepository
    ) {
        $this->storeRepository      = $storeRepository;
        $this->collectionRepository = $collectionRepository;
        $this->groupRepository      = $groupRepository;
        $this->keyRepository        = $keyRepository;
    }
}
