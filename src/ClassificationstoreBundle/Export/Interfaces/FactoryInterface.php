<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Export\Interfaces;

use Pimcore\Model\AbstractModel;

/**
 * Interface FactoryInterface
 * @package Mousset\ClassificationstoreBundle\Export\Interfaces
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
