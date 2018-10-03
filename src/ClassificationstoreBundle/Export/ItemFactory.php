<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export;

use Divante\ClassificationstoreBundle\Export\Interfaces\ItemFactoryInterface;
use Divante\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Divante\ClassificationstoreBundle\Export\Interfaces\KeyFactoryInterface;
use Divante\ClassificationstoreBundle\Export\Item\Collection;
use Divante\ClassificationstoreBundle\Export\Item\Group;
use Divante\ClassificationstoreBundle\Export\Item\Store;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\DataObject\Classificationstore\CollectionConfig;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Classificationstore\StoreConfig;

/**
 * Class ItemFactory
 * @package Divante\ClassificationstoreBundle\Export
 */
class ItemFactory implements ItemFactoryInterface
{
    /**
     * @var KeyFactoryInterface
     */
    private $keyFactory;

    /**
     * @var Store
     */
    private $store;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * @var Group
     */
    private $group;

    /**
     * ItemFactory constructor.
     * @param Store $store
     * @param Collection $collection
     * @param Group $group
     * @param KeyFactoryInterface $keyFactory
     */
    public function __construct(
        Store               $store,
        Collection          $collection,
        Group               $group,
        KeyFactoryInterface $keyFactory
    ) {
        $this->store      = $store;
        $this->collection = $collection;
        $this->group      = $group;
        $this->keyFactory = $keyFactory;
    }

    /**
     * @param AbstractModel $model
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(AbstractModel $model): ItemInterface
    {
        $class = get_class($model);
        $item = null;
        switch ($class) {
            case StoreConfig::class:
                $item = clone $this->store;
                break;
            case CollectionConfig::class:
                $item = clone $this->collection;
                break;
            case GroupConfig::class:
                $item = clone $this->group;
                break;
            case KeyConfig::class:
                $item = $this->keyFactory->getItem($model);
                break;
            default:
                throw new \Exception("Bad item type: " . $class);
                break;
        }
        $item->setModel($model);

        return $item;
    }
}
