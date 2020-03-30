<?php

namespace PHPEc\Constant;

class BasicType
{

    const STRING = 'string';
    const INT = 'int';
    const FLOAT = 'float';
    const BOOL = 'bool';

    const SUPPORT_TYPES = [
        self::STRING, self::INT, self::FLOAT, self::BOOL,
        // 'array' 数组是一个特殊类型，暂时不考虑进来
    ];

    public static function convert(string $type, $value)
    {
        switch ($type) {
            case BasicType::STRING:
                return (string)$value;
            case BasicType::INT:
                return (int)$value;
            case BasicType::FLOAT:
                return (float)$value;
            case BasicType::BOOL:
                return (bool)$value;
            default:
                return $value;
        }
    }
}
