<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Session Container Class
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

/**
 *
 * Class ZgApp\Controller\Plugin\Session
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Session extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param string $namespace
     * @return mixed
     */
    public function __invoke($namespace = 'default')
    {
        $controller = $this->getController();
        if (isset($controller->sessionTab) && !in_array($namespace, $controller->sessionTabExclude)) {
            $namespace .= $controller->sessionTab;
        }
        if (!isset($controller->zgSessionContainer[$namespace])) {
            $controller->zgSessionContainer[$namespace] = new Container($namespace);
        }
        $controller->zgSessionContainer[$namespace]->setDefaultManager($controller->service('sessionManager'));
        return $controller->zgSessionContainer[$namespace];
    }

}

