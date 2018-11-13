<?php
/**
 * @category    ClassificationstoreBundle
 * @date        09/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Component;

use Divante\ClassificationstoreBundle\Component\CsvParser;
use Pimcore\Test\KernelTestCase;

class CsvParserTest extends KernelTestCase
{
    public function test_csvToArray()
    {
        $inData = ['name1', 'value1', 'name2', 'value2', 'name3', 'value3'];
        $outData = CsvParser::csvToArray($inData);
        //print_r($outData);

        $this->assertTrue(is_array($outData));
        $this->assertEquals(3, count($outData));

        $this->assertTrue(isset($outData['name1']));
        $this->assertEquals('value1', $outData['name1']);

        $this->assertTrue(isset($outData['name2']));
        $this->assertEquals('value2', $outData['name2']);

        $this->assertTrue(isset($outData['name3']));
        $this->assertEquals('value3', $outData['name3']);
    }

    public function test_arrayToCsv()
    {
        $inData = [
            'name1' => 'value1',
            'name2' => 'value2',
            'name3' => 'value3'
        ];
        $expectedOutData = ['"name1"', '"value1"', '"name2"', '"value2"', '"name3"', '"value3"'];
        $outData = CsvParser::arrayToCsv($inData, '"');
        $this->assertTrue(is_array($outData));
        $this->assertEquals(6, count($outData));

        for ($iter=0; $iter < 6; ++$iter) {
            $this->assertTrue(isset($outData[$iter]));
            $this->assertEquals($expectedOutData[$iter], $outData[$iter]);
        }
    }
}
