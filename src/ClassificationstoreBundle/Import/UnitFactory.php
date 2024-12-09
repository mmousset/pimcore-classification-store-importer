<?php
/**
 * @category    ClassificationstoreBundle
 * @date        28/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import;

use Mousset\ClassificationstoreBundle\Component\DataWrapper;
use Mousset\ClassificationstoreBundle\Constants;
use Mousset\ClassificationstoreBundle\Import\Interfaces\UnitFactoryInterface;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class UnitFactory
 * @package Mousset\ClassificationstoreBundle\Import
 */
class UnitFactory implements UnitFactoryInterface
{
    private const UNIT_ITEMS_NAMESPACE = "\\Mousset\\ClassificationstoreBundle\\Import\\Item\\";

    /**
     * @param DataWrapper $data
     * @return ItemInterface
     * @throws \Exception
     */
    public function getItem(DataWrapper $data): ItemInterface
    {
        $type = $data->get(Constants::TYPE);
        $class = self::KEYS_ITEMS_NAMESPACE . ucfirst($type);
        if (!class_exists($class)) {
            throw new \Exception("Unit type '$type' not implemented");
        }

        return new $class($data);
    }
}
