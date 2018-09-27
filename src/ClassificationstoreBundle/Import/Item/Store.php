<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Item;

use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Store
 * @package Divante\ClassificationstoreBundle\Import\Item
 */
class Store extends AbstractItem implements ItemInterface
{
    /**
     * @throws \Exception
     */
    public function save(): void
    {
        $name = $this->getName();
        $storeConfig = $this->storeRepository->getByNameOrCreate($name);
        $storeConfig->setDescription($this->getDescription());
        $storeConfig->save();
    }
}
