<?php
/**
 * @category    ClassificationstoreBundle
 * @date        03/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@Mousset.co>
 * @copyright   Copyright (c) 2018 Mousset Ltd. (https://Mousset.co/)
 */

namespace Mousset\ClassificationstoreBundle\Component;

/**
 * Class DataCleaner
 * @package Mousset\ClassificationstoreBundle\Component
 */
class DataCleaner
{
    /**
     * @param array $data
     * @return array
     */
    public static function removeEmptyData(array $data): array
    {
        $outData = [];
        foreach ($data as $name => $value) {
            if ($value) {
                $outData[$name] = $value;
            }
        }

        return $outData;
    }
}
