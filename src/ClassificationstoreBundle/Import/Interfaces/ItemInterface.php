<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Import\Interfaces;

use Divante\ClassificationstoreBundle\Component\DataWrapper;

/**
 * Interface ItemInterface
 * @package Divante\ClassificationstoreBundle\Import\Interfaces
 */
interface ItemInterface
{
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
