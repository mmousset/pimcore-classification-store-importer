<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle;

/**
 * Class Constants
 * @package Divante\ClassificationstoreBundle
 */
interface Constants
{
    public const ITEM            = 'item';
    public const ITEM_STORE      = 'store';
    public const ITEM_COLLECTION = 'collection';
    public const ITEM_GROUP      = 'group';
    public const ITEM_KEY        = 'key';

    public const NAME        = 'name';
    public const TITLE       = 'title';
    public const DESCRIPTION = 'description';
    public const STORE       = 'store';
    public const TYPE        = 'type';
    public const FIELDTYPE   = 'fieldtype';
    public const DATATYPE    = 'datatype';
    public const DATA        = 'data';
    public const PARENT_NAME = 'parentName';

    public const OPTIONS = 'options';

    public const GROUPS = 'groups';
    public const KEYS = 'keys';
    public const DELIMITER = ',';

    public const IMPORT_OPTION_TEXT = 'option_text';
    public const IMPORT_OPTION_VAL  = 'option_value';

    public const DEFINITION_OPTION_TEXT = 'key';
    public const DEFINITION_OPTION_VAL  = 'value';

    public const DEFINITION_YES_LABEL = 'yesLabel';
    public const DEFINITION_NO_LABEL = 'noLabel';
    public const DEFINITION_EMPTY_LABEL = 'emptyLabel';
}
