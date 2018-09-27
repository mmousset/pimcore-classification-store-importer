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
 * Interface FactoryInterface
 * @package Divante\ClassificationstoreBundle\Import\Interfaces
 */
interface FactoryInterface
{
    public function getItem(DataWrapper $data): ItemInterface;
}
