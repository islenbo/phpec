<?php

namespace PHPEc\Support;

interface JsonDeserializable
{

    /**
     * Json Object string deserialize
     *
     * @param string $jsonObjectStr
     * @return JsonDeserializable
     */
    public static function jsonObjectDeserialize(string $jsonObjectStr): JsonDeserializable;

    /**
     * Json Array string deserialize
     *
     * @param string $jsonArrayStr
     * @return JsonDeserializable[]
     */
    public static function jsonArrayDeserialize(string $jsonArrayStr): array;

    /**
     * Iteratorable object decode
     *
     * @param array|object $objectData
     * @return JsonDeserializable
     */
    public static function objectDecode($objectData): JsonDeserializable;

    /**
     * Iteratorable array decode
     *
     * @param array $objectData
     * @return array
     */
    public static function arrayDecode(array $objectData): array;


    /**
     * 转换类型
     *
     * PHP7.4以下版本不支持返回static关键字，导致jsonObjectUnSerialize无法返回具体对象，可通过该方法进行转换
     * 后期如果切换到PHP7.4版本，便可解决该问题
     *
     * @param string $className
     * @param mixed ...$args
     * @return mixed $className对象
     */
    public function convertClassType(string $className, ...$args);
}
