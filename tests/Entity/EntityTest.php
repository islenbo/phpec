<?php

namespace PHPEc\Tests\Entity;

use PHPEc\Support\JsonDeserializable;
use PHPEc\Tests\data\Entity\ProviderUserEntity;
use PHPEc\Tests\data\Entity\UserEntity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{

    /**
     * Test JsonSerialize
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function testJsonSerialize(UserEntity $entity)
    {
        $this->assertEquals($entity->toJson(), json_encode($entity, JSON_UNESCAPED_UNICODE));
        $this->assertNotEquals($entity->toJson(), json_encode($entity));
    }

    /**
     * Test To Json
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function testToJson(UserEntity $entity)
    {
        $this->assertEquals(json_encode($entity->toArray(), JSON_UNESCAPED_UNICODE), $entity->toJson());
        $this->assertNotEquals(json_encode($entity->toArray()), $entity->toJson());
    }

    /**
     * Test Count
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function testCount(UserEntity $entity)
    {
        $this->assertEquals(count($entity->toArray()), count($entity));
        $this->assertNotEquals(count($entity->toArray()) + 1, count($entity));
    }

    /**
     * Test __set
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function test__set(UserEntity $entity)
    {
        $entity->name = '小白';
        $this->assertEquals($entity->name, '小白');
    }

    /**
     * Test __get
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function test__get(UserEntity $entity)
    {
        $this->assertEquals($entity->name, $entity->toArray()['name']);
    }

    /**
     * Test To Array
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity1
     */
    public function testToArray(UserEntity $entity1)
    {
        $this->assertTrue(is_array($entity1->toArray()));
    }

    /**
     * Test Get Iterator
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     * @throws \Exception
     */
    public function testGetIterator(UserEntity $entity)
    {
        $this->assertInstanceOf(\Traversable::class, $entity->getIterator());
        $this->assertNotInstanceOf(self::class, $entity->getIterator());
    }

    /**
     * Test __unset
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function test__unset(UserEntity $entity)
    {
        $this->assertTrue(isset($entity->name));
        unset($entity->name);
        $this->assertFalse(isset($entity->name));
    }

    /**
     * Test __isset
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function test__isset(UserEntity $entity)
    {
        $this->test__unset($entity);
    }

    /**
     * Test __toString
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     */
    public function test__toString(UserEntity $entity)
    {
        $this->assertTrue(json_encode($entity->toArray(), JSON_UNESCAPED_UNICODE) == "{$entity}");
    }

    /**
     * Test Json Object Deserialize
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     * @throws \PHPEc\Exception\JsonException
     * @throws \ReflectionException
     */
    public function testJsonObjectDeserialize(UserEntity $entity)
    {
        $decodeObj = UserEntity::jsonObjectDeserialize($entity->toJson());
        $this->assertInstanceOf(JsonDeserializable::class, $decodeObj);
        $this->assertEquals($decodeObj, $entity);
    }

    /**
     * Test Json Array Deserialize
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity1
     * @param UserEntity $entity2
     * @throws \PHPEc\Exception\JsonException
     * @throws \ReflectionException
     */
    public function testJsonArrayDeserialize(UserEntity $entity1, UserEntity $entity2)
    {
        $jsonArrayStr = json_encode([$entity1, $entity2]);
        $decodeArr = UserEntity::jsonArrayDeserialize($jsonArrayStr);
        $this->assertTrue(is_array($decodeArr));
        $this->assertEquals([$entity1, $entity2], $decodeArr);
    }

    /**
     * Test Object Decode
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity
     * @throws \ReflectionException
     */
    public function testObjectDecode(UserEntity $entity)
    {
        $this->assertEquals(UserEntity::objectDecode($entity->toArray()), $entity);
    }

    public function testObjectDecodePropertyType()
    {
        $this->assertTrue(false, 'TODO 自定义属性匹配');
    }

    /**
     * Test Array Decode
     *
     * @dataProvider providerEntity
     * @param UserEntity $entity1
     * @param UserEntity $entity2
     * @throws \ReflectionException
     */
    public function testArrayDecode(UserEntity $entity1, UserEntity $entity2)
    {
        $this->assertEquals(UserEntity::arrayDecode([$entity1->toArray(), $entity2->toArray()]), [$entity1, $entity2]);
    }

    /**
     * Provider Entity
     *
     * @return array
     */
    public function providerEntity(): array
    {
        return ProviderUserEntity::provider();
    }
}
