<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item\Key;

use Mousset\ClassificationstoreBundle\Component\DataWrapper;
use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Item\Key;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\ClassDefinition\Data\QuantityValue;
use Pimcore\Model\DataObject\QuantityValue\Unit;
/**
 * Class QuantityValue
 * @package Mousset\ClassificationstoreBundle\Import\Item\Key
 */
class QuantityValue extends Key
{
    /**
     * QuantityValue constructor.
     * @param DataWrapper $data
     */
    public function __construct(DataWrapper $data)
    {
        $unit = new Unit();
        $unit->setAbbreviation($data->get('Unit'));
        $unit->save();

        $quantityValue = new QuantityValue();
        $quantityValue->setName($data->get('Attribute code'))
        $quantityValue->setTitle($data->get('Attribute'))

        $keyConfig = new KeyConfig();
        $keyConfig = $keyConfig->setDefinition(json_encode($definition));
        $keyConfig->save(); 
    }

    /**
     * @param KeyConfig $keyConfig
     */
    protected function prepareKeyConfig(KeyConfig $keyConfig)
    {
        parent::prepareKeyConfig($keyConfig);
        $options = $this->getOptions();
        $definition = json_decode($keyConfig->getDefinition(), true);
        $definition[Constants::OPTIONS] = $options;
        $keyConfig->setDefinition(json_encode($definition));
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [];

        for ($iter = 1;; ++$iter) {
            $paramText = Constants::IMPORT_OPTION_TEXT . $iter;
            $paramVal = Constants::IMPORT_OPTION_VAL . $iter;
            $text = $this->get($paramText);
            $value = $this->get($paramVal);
            if (null === $text || null === $value) {
                break;
            }

            $options[] = [
                Constants::DEFINITION_OPTION_TEXT => $text,
                Constants::DEFINITION_OPTION_VAL  => $value
            ];
        }

        return $options;
    }
}
