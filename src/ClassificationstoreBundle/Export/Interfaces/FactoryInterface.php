<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Export\Interfaces;

use Pimcore\Model\AbstractModel;

/**
 * Interface FactoryInterface
 * @package Divante\ClassificationstoreBundle\Export\Interfaces
 */
interface FactoryInterface
{
    /**
     * @param AbstractModel $model
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(AbstractModel $model): ItemInterface;
}
