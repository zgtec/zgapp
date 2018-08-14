<?php

/**
 * Controller Stop Events Propagation Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Stop
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Stop extends AbstractPlugin
{
    public function __invoke()
    {
        $controller = $this->getController();
        if (isset($controller->e) && method_exists($controller->e, 'stopPropagation'))
            $controller->e->stopPropagation();
        return;
    }

}