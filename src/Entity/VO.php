<?php

namespace PHPEc\Entity;

use JsonSerializable;
use PHPEc\Support\Arrayable;
use Traversable;

/**
 * Value-Object
 * VO类的目的，在于通过构造函数对传入的数据进行decode，实现类型转换
 */
class VO extends Entity
{

    /**
     * VO constructor.
     *
     * @param mixed $data
     */
    final public function __construct($data = [])
    {
        self::objectDecode($this->getArrayableItems($data), $this);
    }

    /**
     * Get arrayable items
     * @param mixed $data
     * @return array
     */
    private function getArrayableItems($data): array
    {
        if (is_array($data)) {
            return $data;
        } elseif ($data instanceof self) {
            return $data->toArray();
        } elseif ($data instanceof Arrayable) {
            return $data->toArray();
        } elseif ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        } elseif ($data instanceof Traversable) {
            return iterator_to_array($data);
        }

        return (array)$data;
    }
}
