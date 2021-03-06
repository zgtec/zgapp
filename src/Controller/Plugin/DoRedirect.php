<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * DoRedirect Controller Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\DoRedirect
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class DoRedirect extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $url
     * @param bool $message
     * @param bool $skipdebug
     * @return mixed
     */
    public function __invoke($url, $message = false, $skipdebug = false)
    {
        $controller = $this->getController();

        if ($message) {
            $controller->messenger->addMessage($message);
        }
        $controller->redirect()->toUrl($url);
        $controller->stop();

        return $controller->getResponse();
    }

}
