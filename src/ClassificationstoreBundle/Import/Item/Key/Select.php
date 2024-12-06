<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item\Key;

use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Item\Key;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class Select
 * @package Mousset\ClassificationstoreBundle\Import\Item\Key
 */
class Select extends Key
{
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
