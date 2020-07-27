<?php

namespace PHPEc\Entity;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use PHPEc\Constant\BasicType;
use PHPEc\Exception\JsonException;
use PHPEc\Support\Arrayable;
use PHPEc\Support\Attributeable;
use PHPEc\Support\Jsonable;
use PHPEc\Support\JsonDeserializable;
use PHPEc\Support\Stringable;
use ReflectionClass;
use ReflectionException;
use stdClass;

abstract class Entity implements
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
     * 属性数据
     *
     * @var array
     */
    private $properties = [];

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->getProperties());
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->getProperties());
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
        return $this->getProperties();
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
        return isset($this->properties[$name]);
    }

    /**
     * @inheritDoc
     */
    public function __unset($name)
    {
        unset($this->properties[$name]);
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $propertyType = $this->propertyType();
        if (isset($propertyType[$name])) {
            $type = $propertyType[$name];
            $isNullable = substr($type, 0, 1) == '?';
            if ($isNullable) {
                $type = substr($type, 1, strlen($type));
            }

            $isArray = substr($type, -2) == '[]';
            if ($isArray) {
                $className = substr($type, 0, strlen($type) - 2);
            } else {
                $className = $type;
            }

            if ($isNullable && is_null($value)) {
                $this->properties[$name] = $value;
            } else {
                if ($isArray) {
                    $arr = [];
                    foreach ($value as $item) {
                        $arr[] = $this->setInstanceData($className, $item);
                    }
                    $this->properties[$name] = $arr;
                } else {
                    $this->properties[$name] = $this->setInstanceData($className, $value);
                }
            }
        } else {
            $this->properties[$name] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function &__get($name)
    {
        return $this->properties[$name];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public static function jsonObjectDeserialize(string $jsonObjectStr): JsonDeserializable
    {
        return static::objectDecode(self::jsonDecode($jsonObjectStr));
    }

    /**
     * @inheritDoc
     * @throws JsonException
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
     * @return static
     */
    public static function objectDecode($objectData, ?JsonDeserializable $context = null): JsonDeserializable
    {
        if (is_null($context)) {
            $result = new static();
        } else {
            $result = $context;
        }

        foreach ($objectData as $key => $value) {
            $result->__set($key, $value);
        }

        return $result;
    }

    /**
     * @inheritDoc
     * @return static[]
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
     * @param string $className
     * @param $data
     * @return mixed $instance
     */
    private function setInstanceData($className, $data)
    {
        if (in_array($className, BasicType::SUPPORT_TYPES)) {
            return BasicType::convert($className, $data);
        } else {
            $result = new stdClass();
            try {
                $refClass = new ReflectionClass($className);
                $result = $refClass->newInstance();
            } catch (ReflectionException $e) {
            }

            if ($result instanceof JsonDeserializable) {
                $result = $result->objectDecode($data);
            } else {
                foreach ($data as $key => $value) {
                    $result->$key = $value;
                }
            }
            return $result;
        }
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
     * @throws ReflectionException
     */
    public function convertClassType(string $className, ...$args)
    {
        $ref = new ReflectionClass($className);
        /** @var Entity $result */
        $result = $ref->newInstance(...$args);
        $result->setProperties($this->getProperties());
        return $result;
    }

    /**
     * @param array $property
     */
    public function setProperties(array $property): void
    {
        $this->properties = $property;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * 属性类型
     * 如：
     * [
     *     'user' => UserVO::class, // 普通对象
     *     'orders' => OrderVO::class . '[]', // 对象数组
     *     'size' => 'int', // PHP7内置类型
     * ]
     * @return array
     */
    protected function propertyType(): array
    {
        return [];
    }

}
