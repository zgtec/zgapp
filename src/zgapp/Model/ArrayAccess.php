<?php

namespace ZgApp\Model;

/**
 * Core Array Access Class
 */
class ArrayAccess implements \ArrayAccess
{
    private $_container = array();

    public function __clone()
    {
        foreach ($this->data as $key => $value) {
            if ($value instanceof self) {
                $this[$key] = clone $value;
            }
        }
    }

    /**
     * ArrayAccess constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }
    }

    /**
     * Function offsetSet
     *
     *
     *
     * @param mixed $offset
     * @param mixed $data
     */
    public function offsetSet($offset, $data)
    {
        if (is_array($data)) {
            $data = new self($data);
        }
        if ($offset === null) {
            $this->_container[] = $data;
        } else {
            $this->_container[$offset] = $data;
        }
    }

    /**
     * Function offsetExists
     *
     *
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]);
    }

    /**
     * Function offsetUnset
     *
     *
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
    }

    /**
     * Function offsetGet
     *
     *
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->_container[$offset]) ? $this->_container[$offset] : null;
    }
}
