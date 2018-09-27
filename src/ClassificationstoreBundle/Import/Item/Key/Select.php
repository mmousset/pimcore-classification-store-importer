<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Item\Key;

use Divante\ClassificationstoreBundle\Import\Item\Key;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

/**
 * Class Select
 * @package Divante\ClassificationstoreBundle\Import\Item\Key
 */
class Select extends Key
{
    private const IMPORT_OPTION_TEXT = 'option_text';
    private const IMPORT_OPTION_VAL  = 'option_value';

    private const DEFINITION_OPTION_TEXT = 'key';
    private const DEFINITION_OPTION_VAL  = 'value';

    /**
     * @param KeyConfig $keyConfig
     */
    protected function prepareKeyConfig(KeyConfig $keyConfig)
    {
        parent::prepareKeyConfig($keyConfig);
        $options = $this->getOptions();
        $definition = json_decode($keyConfig->getDefinition(), true);
        $definition['options'] = $options;
        $keyConfig->setDefinition(json_encode($definition));
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [];

        for ($iter = 1;; ++$iter) {
            $paramText = self::IMPORT_OPTION_TEXT . $iter;
            $paramVal = self::IMPORT_OPTION_VAL . $iter;
            $text = $this->get($paramText);
            $value = $this->get($paramVal);
            if (null === $text || null === $value) {
                break;
            }

            $options[] = [
                self::DEFINITION_OPTION_TEXT => $text,
                self::DEFINITION_OPTION_VAL  => $value
            ];
        }

        return $options;
    }
}
