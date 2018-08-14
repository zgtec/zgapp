<?php

/**
 * Alerts controller plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\AlertMessage
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class AlertMessage extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @return array
     */
    public function __invoke()
    {
        $alerts = array();
        foreach ($this->getController()->property->asset->alerts as $code) {
            $alert = (new \Xanterra\Model\Alert\AlertMapper($this->getController()->property->pdomain, $code))->getAsset();
            if ($alert->isVisible()) {
                $alerts[$alert->code] = $alert->message;
            }
        }
        return $alerts;
    }

}
