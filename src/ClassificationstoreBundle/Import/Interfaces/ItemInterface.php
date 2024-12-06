<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Interfaces;

use Mousset\ClassificationstoreBundle\Component\DataWrapper;

/**
 * Interface ItemInterface
 * @package Mousset\ClassificationstoreBundle\Import\Interfaces
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
