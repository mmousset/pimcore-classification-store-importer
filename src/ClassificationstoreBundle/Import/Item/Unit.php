<?php
/**
 * @category    ClassificationstoreBundle
 * @date        27/09/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Import\Item;

use Mousset\ClassificationstoreBundle\Constants;
use Pimcore\Model\DataObject\QuantityValue\Unit;
use Mousset\ClassificationstoreBundle\Import\Interfaces\ItemInterface;

/**
 * Class Store
 * @package Mousset\ClassificationstoreBundle\Import\Item
 */
class Unit extends AbstractItem implements ItemInterface
{
    /**
     * @throws \Exception
     */
    public function save(): void
    {
        $id = $this->getId();
        $unitConfig = $this->UnitRepository->getById($id);
        $unitConfig->save();
    }

    /**
     * @param Unit $unitConfig
     */
    protected function prepareUnitConfig(Unit $unitConfig)
    {
        $attributes = $this->getAllAttributes();
        foreach ($attributes as $name => $value) {
            if ('id' != $name) {
                $setter = 'set' . ucfirst($name);
                if (method_exists($unitConfig, $setter)) {
                    $unitConfig->$setter($value);
                }
            }
        }

        unset($attributes[Constants::ITEM]);
        unset($attributes[Constants::DESCRIPTION]);
        $attributes[Constants::FIELDTYPE] = $attributes[Constants::TYPE];
        $attributes[Constants::DATATYPE] = Constants::DATA;

        $unitConfig->setDefinition(json_encode($attributes));
    }
}