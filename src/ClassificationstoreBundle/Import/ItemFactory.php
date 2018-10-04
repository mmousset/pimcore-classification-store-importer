<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import;

use Divante\ClassificationstoreBundle\Component\DataWrapper;
use Divante\ClassificationstoreBundle\Import\Item\Store;
use Divante\ClassificationstoreBundle\Import\Item\Collection;
use Divante\ClassificationstoreBundle\Import\Item\Group;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemFactoryInterface;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;
use Divante\ClassificationstoreBundle\Import\Interfaces\KeyFactoryInterface;

/**
 * Class ItemFactory
 * @package Divante\ClassificationstoreBundle\Import
 */
class ItemFactory implements ItemFactoryInterface
{
    /**
     * @var KeyFactoryInterface
     */
    private $keyFactory;

    /**
     * ItemFactory constructor.
     * @param KeyFactoryInterface $keyFactory
     */
    public function __construct(KeyFactoryInterface $keyFactory)
    {
        $this->keyFactory = $keyFactory;
    }

    /**
     * @param DataWrapper $data
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(DataWrapper $data): ItemInterface
    {
        $item = $data->get(ItemInterface::ITEM);
        switch ($item) {
            case ItemInterface::ITEM_STORE:
                return new Store($data);
                break;
            case ItemInterface::ITEM_COLLECTION:
                return new Collection($data);
                break;
            case ItemInterface::ITEM_GROUP:
                return new Group($data);
                break;
            case ItemInterface::ITEM_KEY:
                return $this->keyFactory->getItem($data);
                break;
            default:
                throw new \Exception("Unknown item type: " . $item);
                break;
        }
    }
}
