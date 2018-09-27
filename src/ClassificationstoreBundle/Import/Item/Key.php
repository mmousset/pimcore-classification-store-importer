<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Item;

use Divante\ClassificationstoreBundle\Import\Interfaces\ItemInterface;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class Key
 * @package Divante\ClassificationstoreBundle\Import\Item
 */
class Key extends AbstractItem implements ItemInterface
{
    /**
     *
     */
    public function save(): void
    {
        $name = $this->getName();
        $store = $this->getStore();
        $keyConfig = $this->keyRepository->getByNameOrCreate($name, $store);
        $this->prepareKeyConfig($keyConfig);
        $keyConfig->save();
    }

    /**
     * @param KeyConfig $keyConfig
     */
    protected function prepareKeyConfig(KeyConfig $keyConfig)
    {
        $attributes = $this->getAllAttributes();
        foreach ($attributes as $name => $value) {
            if ('id' != $name) {
                $setter = 'set' . ucfirst($name);
                if (method_exists($keyConfig, $setter)) {
                    $keyConfig->$setter($value);
                }
            }
        }

        unset($attributes[ItemInterface::ITEM]);
        unset($attributes[ItemInterface::DESCRIPTION]);
        unset($attributes[ItemInterface::TITLE]);

        $keyConfig->setDefinition(json_encode($attributes));
    }
}
