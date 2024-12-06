<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @author      Agata Drozdek <adrozdek@Mousset.pl>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item\Key;

use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Item\KeyWithDefaults;

/**
 * Class BooleanSelect
 * @package Mousset\ClassificationstoreBundle\Import\Item\Key
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
