<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import;

use Divante\ClassificationstoreBundle\Component\DataWrapper;
use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;
use Divante\ClassificationstoreBundle\Import\Interfaces\KeyFactoryInterface;

/**
 * Class KeyFactory
 * @package Divante\ClassificationstoreBundle\Import
 */
class KeyFactory implements KeyFactoryInterface
{
    private const KEYS_ITEMS_NAMESPACE = "\\Divante\\ClassificationstoreBundle\\Import\\Item\\Key\\";

    /**
     * @param DataWrapper $data
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(DataWrapper $data): ItemInterface
    {
        $type = $data->get(ItemInterface::TYPE);
        $class = self::KEYS_ITEMS_NAMESPACE . ucfirst($type);
        if (!class_exists($class)) {
            throw new \Exception("Key type '$type' not implemented");
        }

        return new $class($data);
    }
}
