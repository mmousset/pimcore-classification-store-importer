<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item;

use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Store
 * @package Mousset\ClassificationstoreBundle\Import\Item
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
