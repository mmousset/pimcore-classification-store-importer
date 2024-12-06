<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Export\Item;

use Mousset\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Mousset\ClassificationstoreBundle\Repository\CollectionRepository;
use Mousset\ClassificationstoreBundle\Repository\GroupRepository;
use Mousset\ClassificationstoreBundle\Repository\KeyRepository;
use Mousset\ClassificationstoreBundle\Repository\StoreRepository;

/**
 * Class AbstractItem
 * @package Mousset\ClassificationstoreBundle\Export\Item
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
