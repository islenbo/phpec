<?php

namespace PHPEc\Collection;

use ArrayAccess;
use Countable;
use PHPEc\Constant\BasicType;
use PHPEc\Exception\NotSupportedException;
use PHPEc\Support\Arrayable;

class Collection implements ArrayAccess, Arrayable, Countable
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Collection constructor.
     *
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Append
     *
     * @param mixed $value
     * @return Collection
     */
    public function append($value): self
    {
        $this->offsetSet(null, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        if (in_array($this->type, BasicType::SUPPORT_TYPES)) {
            $value = BasicType::convert($this->type, $value);
        } elseif (!($value instanceof $this->type)) {
            throw new NotSupportedException("类型必须为 {$this->type}，当前传入的类型为 " . get_class($value));
        }

        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[intval($offset)] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
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
    public function count()
    {
        return count($this->toArray());
    }
}
