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

}
