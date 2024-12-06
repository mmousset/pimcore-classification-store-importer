<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item;

use Mousset\ClassificationstoreBundle\Constants;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Key
 * @package Mousset\ClassificationstoreBundle\Import\Item
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

        unset($attributes[Constants::ITEM]);
        unset($attributes[Constants::DESCRIPTION]);
        $attributes[Constants::FIELDTYPE] = $attributes[Constants::TYPE];
        $attributes[Constants::DATATYPE] = Constants::DATA;

        $keyConfig->setDefinition(json_encode($attributes));
    }
}
