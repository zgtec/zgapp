<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * IsAjax Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class ZgApp\Controller\Plugin\IsAjax
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class IsAjax extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param bool $stop
     * @return mixed
     */
    public function __invoke($stop = false)
    {
        if (!$stop) {
            return $this->getController()->getRequest()->isXmlHttpRequest();
        }

        if (!$this->getController()->getRequest()->isXmlHttpRequest()) {
            die($this->getController()->render('zg-app/zg-app/errors/denied'));
        }
        return true;
    }

}
