<?php
/**
 * @category    ClassificationstoreBundle
 * @date        02/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export\Item;

use Divante\ClassificationstoreBundle\Constants;
use Divante\ClassificationstoreBundle\Export\Interfaces\ItemInterface;
use Divante\ClassificationstoreBundle\Repository\StoreRepository;
use Pimcore\Model\AbstractModel;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class Key
 * @package Divante\ClassificationstoreBundle\Export\Item
 */
class Key implements ItemInterface
{
    /**
     * @var KeyConfig
     */
    protected $keyConfig;

    /**
     * @param AbstractModel $model
     * @throws \Exception
     */
    public function setModel(AbstractModel $model)
    {
        if (!($model instanceof KeyConfig)) {
            throw new \Exception("Bad type, expected " . KeyConfig::class . " got: " . get_class($model));
        }

        $this->keyConfig = $model;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $storeName =
            \Pimcore::getContainer()->get(StoreRepository::class)->getById($this->keyConfig->getStoreId())->getName();

        $data = [
            Constants::ITEM        => Constants::ITEM_KEY,
            Constants::STORE       => $storeName,
            Constants::NAME        => $this->keyConfig->getName(),
            Constants::DESCRIPTION => $this->keyConfig->getDescription(),
            Constants::TYPE        => $this->keyConfig->getType(),
            Constants::TITLE       => $this->keyConfig->getTitle(),
        ];

        $definition = json_decode($this->keyConfig->getDefinition(), true);
        $data = $data + $definition;

        return $data;
    }
}
