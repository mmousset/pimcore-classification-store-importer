<?php
/**
 * @category    ClassificationstoreBundle
 * @date        22/10/2018 10:15
 * @author      Wojciech Peisert <wpeisert@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

namespace Divante\ClassificationstoreBundle\Test\Component;

use Divante\ClassificationstoreBundle\Component\FieldWrapperInterface;
use Divante\ClassificationstoreBundle\Repository\CollectionRepository;
use Divante\ClassificationstoreBundle\Repository\GroupRepository;
use Divante\ClassificationstoreBundle\Repository\KeyRepository;
use Divante\ClassificationstoreBundle\Repository\StoreRepository;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Test\KernelTestCase;

class FieldWrapperTest extends KernelTestCase
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @var CollectionRepository
     */
    protected $collectionRepository;

    /**
     * @var GroupRepository
     */
    protected $groupRepository;

    /**
     * @var KeyRepository
     */
    protected $keyRepository;

    private const CLASS_NAME = 'Class1';
    private const OBJECT_NAME = 'obj1';
    private const FIELD_NAME = 'field1';
    private const STORE1_NAME = 'store1';
    private const STORE2_NAME = 'store2';
    private const GROUP1_NAME = 'group1';
    private const GROUP2_NAME = 'group2';
    private const INPUT1_NAME = 'input1';
    private const INPUT2_NAME = 'input2';
    private const SELECT1_NAME = 'select1';
    private const SELECT2_NAME = 'select2';

    /**
     * @var FieldWrapperInterface
     */
    private $fieldWrapper;

    /**
     * @var Concrete
     */
    private $object;

    /**
     * @throws \Exception
     */
    public function test_set_get()
    {
        $value1 = 'abcd 1234';
        $value2 = 'qwerty 1234';
        $this->fieldWrapper->set(self::GROUP1_NAME, self::INPUT1_NAME, $value1);
        $this->fieldWrapper->set(self::GROUP2_NAME, self::INPUT2_NAME, $value2);
        $this->assertEquals($value1, $this->fieldWrapper->get(self::GROUP1_NAME, self::INPUT1_NAME));
        $this->assertEquals($value2, $this->fieldWrapper->get(self::GROUP2_NAME, self::INPUT2_NAME));
    }

    /**
     * @throws \Exception
     */
    public function test_add_remove_0()
    {
        $value1 = 'val 1';

        $this->fieldWrapper->add(self::GROUP1_NAME, self::SELECT1_NAME, $value1);
        $value = $this->fieldWrapper->get(self::GROUP1_NAME, self::SELECT1_NAME);
        $this->assertEquals($value1, $value);
    }

    /**
     * @throws \Exception
     */
    public function test_add_remove()
    {
        $value1 = 'val 1';
        $value2 = 'val 2';

        $this->fieldWrapper->add(self::GROUP1_NAME, self::SELECT1_NAME, $value1);
        $value = $this->fieldWrapper->get(self::GROUP1_NAME, self::SELECT1_NAME);
        $this->assertEquals($value1, $value);

        $this->fieldWrapper->add(self::GROUP1_NAME, self::SELECT1_NAME, $value2);
        $this->assertEquals("$value1,$value2", $this->fieldWrapper->get(self::GROUP1_NAME, self::SELECT1_NAME));

        $this->fieldWrapper->remove(self::GROUP1_NAME, self::SELECT1_NAME, $value1);
        $this->assertEquals($value2, $this->fieldWrapper->get(self::GROUP1_NAME, self::SELECT1_NAME));

        $this->fieldWrapper->remove(self::GROUP1_NAME, self::SELECT1_NAME, $value2);
        $this->assertEquals("", $this->fieldWrapper->get(self::GROUP1_NAME, self::SELECT1_NAME));

        $this->assertEquals("", $this->fieldWrapper->get(self::GROUP2_NAME, self::SELECT2_NAME));
    }

    /**
     * @throws \Exception
     */
    public function test_getAllValues()
    {
        $value1 = 'abcd 1234';
        $value2 = 'qwerty 1234';
        $value3 = 'val 1';
        $value4 = 'val 2';
        $this->fieldWrapper->set(self::GROUP1_NAME, self::INPUT1_NAME, $value1);
        $this->fieldWrapper->set(self::GROUP2_NAME, self::INPUT2_NAME, $value2);
        $this->fieldWrapper->add(self::GROUP1_NAME, self::SELECT1_NAME, $value3);
        $this->fieldWrapper->add(self::GROUP2_NAME, self::SELECT2_NAME, $value4);
        $this->fieldWrapper->add(self::GROUP2_NAME, self::SELECT2_NAME, $value1);

        $values = $this->fieldWrapper->getAllValues();
        $this->assertEquals(4, count($values));

        $this->assertTrue(isset($values[self::INPUT1_NAME]));
        $this->assertEquals($value1, $values[self::INPUT1_NAME]);

        $this->assertTrue(isset($values[self::INPUT2_NAME]));
        $this->assertEquals($value2, $values[self::INPUT2_NAME]);


        $this->assertTrue(isset($values[self::SELECT1_NAME]));
        $this->assertEquals($value3, $values[self::SELECT1_NAME]);

        $this->assertTrue(isset($values[self::SELECT2_NAME]));
        $this->assertEquals($value4 . ',' . $value1, $values[self::SELECT2_NAME]);
    }

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        $this->fieldWrapper = null;
        $this->initServices();
        $this->createClassificationstore();
        $this->createClass();
        $this->createObject();
        $this->createFieldWrapper();
    }

    protected function tearDown()
    {
        $this->removeObject();
        $this->removeClass();
    }

    private function initServices()
    {
        self::bootKernel();

        $this->storeRepository = static::$kernel
            ->getContainer()
            ->get(StoreRepository::class);

        $this->collectionRepository = static::$kernel
            ->getContainer()
            ->get(CollectionRepository::class);

        $this->groupRepository = static::$kernel
            ->getContainer()
            ->get(GroupRepository::class);

        $this->keyRepository = static::$kernel
            ->getContainer()
            ->get(KeyRepository::class);

        $this->fieldWrapper = static::$kernel
            ->getContainer()
            ->get(FieldWrapperInterface::class);
    }

    private function createClassificationstore()
    {
        foreach ([self::STORE1_NAME, self::STORE2_NAME] as $storeName) {
            foreach ([self::GROUP1_NAME, self::GROUP2_NAME] as $groupName) {
                foreach ([self::INPUT1_NAME, self::INPUT2_NAME] as $keyName) {
                    $this->groupRepository->addKeyToGroup($keyName, $groupName, $storeName);
                }
                foreach ([self::SELECT1_NAME, self::SELECT2_NAME] as $keyName) {
                    $keyConfig = $this->keyRepository->getByNameOrCreate($keyName, $storeName);
                    $keyConfig->setType('select');
                    $definition = json_decode($keyConfig->getDefinition(), true);
                    $definition['itemtype'] = 'select';
                    $keyConfig->setDefinition(json_encode($definition));
                    $keyConfig->save();
                    $this->groupRepository->addKeyToGroup($keyName, $groupName, $storeName);
                }
            }
        }
    }

    private function createClass()
    {
        $storeName = $this->storeRepository->getByNameOrCreate(self::STORE1_NAME)->getName();
        $group1 = $this->groupRepository->getByNameOrCreate(self::GROUP1_NAME, $storeName);
        $group2 = $this->groupRepository->getByNameOrCreate(self::GROUP2_NAME, $storeName);
        $allowedGroupIds = $group1->getId() . ',' . $group2->getId();

        $def = file_get_contents('tests/unit/files/class_export.json');
        $placeholders = [
            'CLASS_ID'          => self::CLASS_NAME,
            'CS_FIELD_NAME'     => self::FIELD_NAME,
            'STORE_ID'          => $this->storeRepository->getByNameOrCreate(self::STORE1_NAME)->getId(),
            'ALLOWED_GROUP_IDS' => $allowedGroupIds
        ];
        foreach ($placeholders as $name => $value) {
            $def = str_replace('%%' . $name . '%%', $value, $def);
        }
        $this->createClassDefinitionFromString(self::CLASS_NAME, $def);
    }

    /**
     * @throws \Exception
     */
    private function createObject()
    {
        $this->removeObject();
        $class = '\\Pimcore\\Model\\DataObject\\' . self::CLASS_NAME;
        /** @var Concrete $object */
        $object = new $class();
        $object->setKey(self::OBJECT_NAME);
        $object->setParentId(1);
        $object->save();

        $this->object = $object;
    }

    private function removeClass()
    {
        // $this->installerService->removeClassDefinition(self::CLASS_NAME);
    }

    private function removeObject()
    {
        $object = Concrete::getByPath('/' . self::OBJECT_NAME);
        if ($object) {
            $object->delete();
        }
    }

    /**
     * @throws \Exception
     */
    private function createFieldWrapper()
    {
        $this->fieldWrapper->setUp($this->object, self::FIELD_NAME);
    }

    /**
     * @param string $name
     * @param string $json
     * @return bool
     */
    private function createClassDefinitionFromString(string $name, string $json)
    {
        $class = null;
        try {
            $class = ClassDefinition::getByName($name);
        } catch (\Exception $e) {
            // ignore
        }
        if (false === $class instanceof ClassDefinition) {
            $class = ClassDefinition::create(['name' => $name, 'userOwner' => 0]);
        }

        $success = ClassDefinition\Service::importClassDefinitionFromJson($class, $json);

        return $success;
    }
}
