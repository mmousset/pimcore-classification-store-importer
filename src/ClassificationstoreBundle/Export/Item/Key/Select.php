<?php
/**
 * @category    ClassificationstoreBundle
 * @date        04/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export\Item\Key;

use Divante\ClassificationstoreBundle\Constants;
use Divante\ClassificationstoreBundle\Export\Item\Key;

/**
 * Class Select
 * @package Divante\ClassificationstoreBundle\Export\Item\Key
 */
class Select extends Key
{
    /**
     * @return array
     */
    public function getData(): array
    {
        $data = parent::getData();
        unset($data[Constants::OPTIONS]);

        return array_merge($data, $this->getOptions());
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $data = [];

        $definition = json_decode($this->keyConfig->getDefinition(), true);
        $options = $definition[Constants::OPTIONS];

        $currNo = 1;
        foreach ($options as $option) {
            $paramText = Constants::IMPORT_OPTION_TEXT . $currNo;
            $paramVal = Constants::IMPORT_OPTION_VAL . $currNo;
            $text = $option[Constants::DEFINITION_OPTION_TEXT];
            $value = $option[Constants::DEFINITION_OPTION_VAL];
            $data[$paramText] = $text;
            $data[$paramVal] = $value;
            ++$currNo;
        }

        return $data;
    }
}
