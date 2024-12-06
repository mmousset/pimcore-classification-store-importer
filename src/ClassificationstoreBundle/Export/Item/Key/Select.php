<?php
/**
 * @category    ClassificationstoreBundle
 * @date        04/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Export\Item\Key;

use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Export\Item\Key;

/**
 * Class Select
 * @package Mousset\ClassificationstoreBundle\Export\Item\Key
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
