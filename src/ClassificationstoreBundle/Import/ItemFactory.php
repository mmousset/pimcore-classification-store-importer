<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import;

use Mousset\ClassificationstoreBundle\Component\DataWrapper;
use Mousset\ClassificationstoreBundle\Import\Item\Store;
use Mousset\ClassificationstoreBundle\Import\Item\Collection;
use Mousset\ClassificationstoreBundle\Import\Item\Group;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemFactoryInterface;
use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Interfaces\KeyFactoryInterface;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class ItemFactory
 * @package Mousset\ClassificationstoreBundle\Import
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
     * @param UnitFactoryInterface $unitFactory
     */
    public function __construct(KeyFactoryInterface $keyFactory, UnitFactoryInterface $unitFactory)
    {
        $this->keyFactory = $keyFactory;
        $this->unitFactory = $unitFactory;
    }

    /**
     * @param DataWrapper $data
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(DataWrapper $data): ItemInterface
    {
        $item = $data->get(Constants::ITEM);
        switch ($item) {
            case Constants::ITEM_STORE:
                return new Store($data);
                break;
            case Constants::ITEM_COLLECTION:
                return new Collection($data);
                break;
            case Constants::ITEM_GROUP:
                return new Group($data);
                break;
            case Constants::ITEM_KEY:
                return $this->keyFactory->getItem($data);
                break;
            case Constants::ITEM_UNIT:
                return $this->unitFactory->getItem($data);
                break;
            default:
                throw new \Exception("Unknown item type: " . $item);
                break;
        }
    }
}
