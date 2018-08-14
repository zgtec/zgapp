<?php

/**
 * Arrays functions Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Arrays
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Arrays extends AbstractPlugin
{

    public $source;

    /**
     * Function __invoke
     *
     *
     *
     * @param array $source
     * @return $this
     */
    public function __invoke(array $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Function moveUpAssotiative
     *
     *
     *
     * @param $key
     * @return array
     */
    public function moveUpAssotiative($key)
    {
        $result = array();
        $current = array($key, $this->source[$key]);
        $position = array_search($key, array_keys($this->source));

        if ($position > 0) {
            $index = 0;
            foreach ($this->source as $k => $v) {
                if ($index === $position - 1) {
                    $swap = array($k, $v);
                    $result[$current[0]] = $current[1];
                } elseif ($index === $position) {
                    $result[$swap[0]] = $swap[1];
                } else {
                    $result[$k] = $v;
                }
                $index++;
            }
        } else {
            $result = $this->source;
        }
        return $result;
    }

    /**
     * Function moveDownAssotiative
     *
     *
     *
     * @param $key
     * @return array
     */
    public function moveDownAssotiative($key)
    {
        $result = array();
        $current = array($key, $this->source[$key]);
        $position = array_search($key, array_keys($this->source));
        if ($position < count($this->source) - 1) {
            $index = 0;
            foreach ($this->source as $k => $v) {
                if ($index === $position + 1) {
                    $swap = array($k, $v);
                }
                $index++;
            }
            $index = 0;
            foreach ($this->source as $k => $v) {
                if ($index === $position) {
                    $result[$swap[0]] = $swap[1];
                } elseif ($index === $position + 1) {
                    $result[$current[0]] = $current[1];
                } else {
                    $result[$k] = $v;
                }
                $index++;
            }
        } else {
            $result = $this->source;
        }
        return $result;
    }

}
