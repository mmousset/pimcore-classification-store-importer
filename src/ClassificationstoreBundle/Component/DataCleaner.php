<?php
/**
 * @category    ClassificationstoreBundle
 * @date        03/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Component;

/**
 * Class DataCleaner
 * @package Divante\ClassificationstoreBundle\Component
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
