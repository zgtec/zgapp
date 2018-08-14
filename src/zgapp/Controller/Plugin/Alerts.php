<?php

/**
 * AppHealth Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Alerts
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Alerts extends AbstractPlugin
{
    protected $event;
    protected $key = false;
    protected $cacher;
    protected $threshold;
    protected $priority;

    /**
     * Function __invoke
     *
     *
     *
     * @param $event
     * @return $this|bool
     */
    public function __invoke($event)
    {
        if (!isset($this->getController()->mainconfig['alerts'][$event])) {
            return false;
        }

        $this->event = $event;
        $this->key = $event . $this->getController()->mainconfig['alerts'][$event]['key'];
        $this->threshold = $this->getController()->mainconfig['alerts'][$event]['threshold'];
        $this->priority = $this->getController()->mainconfig['alerts'][$event]['priority'];
        $this->cacher = $this->getController()->cacheFile();

        return $this;
    }

    /**
     * Function failed
     *
     *
     *
     * @return bool
     */
    public function failed()
    {
        if (!$this->key) {
            return false;
        }
        $count = $this->cacher->getItem($this->key);
        if ($count >= $this->threshold) {
            $this->getController()->eventlog->logEvent($this->event, "ALERT", $this->priority, false, false, 'APPLICATION', "THRESHOLD REACHED: " . $count);
            $count = 0;
        }
        $this->cacher->setItem($this->key, $count + 1);
    }

    /**
     * Function success
     *
     *
     *
     * @return bool
     */
    public function success()
    {
        if (!$this->key) {
            return false;
        }
        $count = $this->cacher->getItem($this->key);
        $this->cacher->setItem($this->key, ($count > 0) ? $count - 1 : 0);
    }

}