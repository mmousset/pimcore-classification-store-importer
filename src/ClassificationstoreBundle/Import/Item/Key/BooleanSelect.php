<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @author      Agata Drozdek <adrozdek@divante.pl>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Item\Key;

use Divante\ClassificationstoreBundle\Constants;
use Divante\ClassificationstoreBundle\Import\Item\KeyWithDefaults;

/**
 * Class BooleanSelect
 * @package Divante\ClassificationstoreBundle\Import\Item\Key
 */
class BooleanSelect extends KeyWithDefaults
{
    /**
     * @return array
     */
    public function getDefaultData(): array
    {
        $options = [
            Constants::DEFINITION_YES_LABEL   => 'yes',
            Constants::DEFINITION_NO_LABEL    => 'no',
            Constants::DEFINITION_EMPTY_LABEL => 'empty',
        ];

        return $options;
    }
}
