<?php
/**
 * @category    ClassificationstoreBundle
 * @date        02/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export;

use Divante\ClassificationstoreBundle\Export\Interfaces\KeyFactoryInterface;
use Divante\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Divante\ClassificationstoreBundle\Export\Item\Key;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class KeyFactory
 * @package Divante\ClassificationstoreBundle\Export
 */
class KeyFactory implements KeyFactoryInterface
{
    private const KEYS_ITEMS_NAMESPACE = "\\Divante\\ClassificationstoreBundle\\Export\\Item\\Key\\";

    /**
     * @param AbstractModel $model
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(AbstractModel $model): ItemInterface
    {
        if (!($model instanceof KeyConfig)) {
            throw new \Exception("Bad type, expected KeyConfig, got:" . get_class($model));
        }

        $type = $model->getType();
        $class = self::KEYS_ITEMS_NAMESPACE . ucfirst($type);
        if (!class_exists($class)) {
            throw new \Exception("Key type '$type' not implemented");
        }

        /** @var Key $item */
        $item = new $class();
        $item->setModel($model);

        return $item;
    }
}
