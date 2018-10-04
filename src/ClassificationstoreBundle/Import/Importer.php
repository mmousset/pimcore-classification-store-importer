<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import;

use Divante\ClassificationstoreBundle\Component\DataWrapper;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemFactoryInterface;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Importer
 * @package Divante\ClassificationstoreBundle\Import
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
    public function __construct(ItemFactoryInterface $itemFactory)
    {
        $this->itemFactory = $itemFactory;
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
}
