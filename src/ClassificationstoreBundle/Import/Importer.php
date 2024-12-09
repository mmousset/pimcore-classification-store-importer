<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import;

use Mousset\ClassificationstoreBundle\Component\DataWrapper;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemFactoryInterface;
use Mousset\ClassificationstoreBundle\Import\Interfaces\KeyFactoryInterface;
use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Importer
 * @package Mousset\ClassificationstoreBundle\Import
 */
class Importer
{
    /**
     * @var ItemFactoryInterface
     */
    private $itemFactory;

    /**
     * Importer constructor.
     * @param ItemFactoryInterface $itemFactory
     */
    public function __construct(ItemFactoryInterface $itemFactory, KeyFactoryInterface $keyFactory)
    {
        $this->itemFactory = $itemFactory;
        $this->keyFactory = $keyFactory;
    }

    /**
     * @param DataWrapper $data
     * @return ItemInterface
     */
    public function importItem(DataWrapper $data): ItemInterface
    {
        $item = $this->itemFactory->getItem($data);
        $item->save();

        return $item;
    }

    /**
     * @param DataWrapper $data
     * @return KeyFactoryInterface
     */
    public function importKey(DataWrapper $data): KeyFactoryInterface
    {
        $key = $this->keyFactory->createKey($data);
        $key->save();

        return $key;
    }
}
