<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item;

use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Collection
 * @package Mousset\ClassificationstoreBundle\Import\Item
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
        $groups = $this->get(Constants::GROUPS);
        if (trim($groups)) {
            foreach (explode(Constants::DELIMITER, $groups) as $group) {
                $this->collectionRepository->addGroupToCollection(trim($group), $name, $store);
            }
        }

        $collectionConfig->save();
    }
}
