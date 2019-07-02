<?php
/**
 * @category    ClassificationstoreBundle
 * @date        26.06.19
 * @author      Agata Drozdek <adrozdek@divante.pl>
 * @copyright   Copyright (c) 2019 Divante Ltd. (https://divante.co)
 */

namespace Divante\ClassificationstoreBundle\Import\Item;

use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class KeyWithDefaults
 * @package Divante\ClassificationstoreBundle\Import\Item
 */
abstract class KeyWithDefaults extends Key
{
    /**
     * @param KeyConfig $keyConfig
     */
    protected function prepareKeyConfig(KeyConfig $keyConfig)
    {
        parent::prepareKeyConfig($keyConfig);
        $definition = json_decode($keyConfig->getDefinition(), true);
        $this->addDefaultData($definition);

        $keyConfig->setDefinition(json_encode($definition));
    }

    /**
     * @param array $definition
     */
    protected function addDefaultData(array &$definition)
    {
        foreach ($this->getDefaultData() as $key => $label) {
            if (!array_key_exists($key, $definition)) {
                $definition[$key] = $label;
            }
        }
    }

    /**
     * @return array
     */
    abstract protected function getDefaultData(): array;
}
