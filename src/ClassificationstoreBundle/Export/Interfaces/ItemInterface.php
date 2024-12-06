<?php
/**
 * @category    ClassificationstoreBundle
 * @date        01/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Export\Interfaces;

use Pimcore\Model\AbstractModel;

interface ItemInterface
{
    /**
     * @param AbstractModel $model
     * @throws \Exception
     */
    public function setModel(AbstractModel $model);

    /**
     * @return array
     */
    public function getData(): array;
}
