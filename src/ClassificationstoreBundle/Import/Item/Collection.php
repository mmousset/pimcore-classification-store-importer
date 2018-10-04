<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Item;

use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Collection
 * @package Divante\ClassificationstoreBundle\Import\Item
 */
class Collection extends AbstractItem implements ItemInterface
{
    /**
     *
     */
    public function save(): void
    {
        $name = $this->getName();
        $store = $this->getStore();
        $collectionConfig = $this->collectionRepository->getByNameOrCreate($name, $store);
        $collectionConfig->setDescription($this->getDescription());
        $groups = $this->get(self::GROUPS);
        if (trim($groups)) {
            foreach (explode(self::DELIMITER, $groups) as $group) {
                $this->collectionRepository->addGroupToCollection(trim($group), $name, $store);
            }
        }

        $collectionConfig->save();
    }
}
