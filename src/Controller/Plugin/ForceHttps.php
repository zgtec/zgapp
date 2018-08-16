<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * ForceHttps Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Uri\Http as HttpUri;

/**
 *
 * Class ZgApp\Controller\Plugin\ForceHttps
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class ForceHttps extends AbstractPlugin
{

    public function __invoke()
    {
        if ($this->getController()->mainconfig['session']['cookie_secure'] !== 'true' || $this->getController()->getRequest()->getUri()->getScheme() === 'https') {
            return false;
        }

        // Not secure, create full url
        $url = $this->getController()->url()->fromRoute(null, array(), array('force_canonical' => true,), true);
        $url = new HttpUri($url);
        $url->setScheme('https');
        return $this->getController()->redirect()->toUrl($url);
    }

}
