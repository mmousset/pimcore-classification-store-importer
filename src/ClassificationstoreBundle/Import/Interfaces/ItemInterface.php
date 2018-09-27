<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Interfaces;

use Divante\ClassificationstoreBundle\Component\DataWrapper;

interface ItemInterface
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

    public const GROUPS = 'groups';
    public const KEYS = 'keys';
    public const DELIMITER = ',';

    /**
     * @param DataWrapper $data
     * @throws \Exception
     */
    public function __construct(DataWrapper $data);

    /**
     * @return string
     */
    public function getItemType(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return null|string
     */
    public function getDescription(): ?string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     *
     */
    public function save(): void;
}
