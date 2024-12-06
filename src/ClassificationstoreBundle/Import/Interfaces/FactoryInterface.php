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
 * Interface FactoryInterface
 * @package Mousset\ClassificationstoreBundle\Import\Interfaces
 */
interface FactoryInterface
{
    public function getItem(DataWrapper $data): ItemInterface;
}
