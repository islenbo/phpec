<?php

namespace PHPEc\Entity;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use PHPEc\Exception\JsonException;
use PHPEc\Support\Arrayable;
use PHPEc\Support\Attributeable;
use PHPEc\Support\Jsonable;
use PHPEc\Support\JsonDeserializable;
use PHPEc\Support\Stringable;
use ReflectionClass;

class Entity implements
    JsonSerializable,
    IteratorAggregate,
    Countable,
    Arrayable,
    Jsonable,
    Stringable,
    Attributeable,
    JsonDeserializable
{

    /**
     * Attributes
     *
     * @var array
     */
    protected $data = [];

    /**
     * 属性类型
     * 如：
     * [
     *     'user' => UserVO::class, // 普通对象
     *     'orders' => OrderVO::class . '[]', // 对象数组
     * ]
     * @var array
     */
    protected $propertyType = [];

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function toJson(int $options = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @inheritDoc
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * @inheritDoc
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function &__get($name)
    {
        return $this->data[$name];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     * @throws \ReflectionException
     */
    public static function jsonObjectDeserialize(string $jsonObjectStr): JsonDeserializable
    {
        return static::objectDecode(self::jsonDecode($jsonObjectStr));
    }

    /**
     * @inheritDoc
     * @throws JsonException
     * @throws \ReflectionException
     */
    public static function jsonArrayDeserialize(string $jsonArrayStr): array
    {
        $result = [];
        foreach (self::jsonDecode($jsonArrayStr) as $item) {
            $result[] = static::objectDecode($item);
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public static function objectDecode($objectData): JsonDeserializable
    {
        $result = new static();
        foreach ($objectData as $key => $value) {
            /*
             * TODO
             * 1、当$result->propertyType[$key]不存在时，可以尝试从doc中获取类型
             * 2、基础数据类型：int float string 等也需要类型转换
             */

            if (isset($result->propertyType[$key])) {
                $className = $result->propertyType[$key];
                $isArray = substr($className, -2) == '[]';
                if ($isArray) {
                    $className = substr($className, 0, strlen($className) - 2);
                }

                $refClass = new ReflectionClass($className);
                if ($isArray) {
                    $arr = [];
                    foreach ($value as $item) {
                        $arr[] = $result->setInstanceData($refClass->newInstance(), $item);
                    }
                    $result->$key = $arr;
                } else {
                    $result->$key = $result->setInstanceData($refClass->newInstance(), $value);
                }
            } else {
                $result->$key = $value;
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public static function arrayDecode(array $objectData): array
    {
        $result = [];
        foreach ($objectData as $objectDatum) {
            $result[] = self::objectDecode($objectDatum);
        }
        return $result;
    }

    /**
     * Set instance data
     * @param $instance
     * @param $data
     * @return mixed $instance
     */
    private function setInstanceData($instance, $data)
    {
        if ($instance instanceof JsonDeserializable) {
            $instance->objectDecode($data);
        } else {
            foreach ($data as $key => $value) {
                $instance->$key = $value;
            }
        }

        return $instance;
    }

    /**
     * Json decode
     * @param string $jsonStr
     * @return array|object
     * @throws JsonException
     */
    private static function jsonDecode(string $jsonStr)
    {
        $result = json_decode($jsonStr);
        if (json_last_error() != JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg(), json_last_error());
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function convertClassType(string $className, ...$args)
    {
        $ref = new ReflectionClass($className);
        /** @var Entity $newObj */
        $newObj = $ref->newInstance(...$args);
        $newObj->data = $this->data;
        return $newObj;
    }
}
