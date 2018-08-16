<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Helper Caller Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\Helper
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Helper extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $name
     * @param array $options
     * @return mixed
     */
    public function __invoke($name, $options = array())
    {
        $controller = $this->getController();
        $viewHelperManager = $controller->getContainer()->get('ViewHelperManager');
        return $viewHelperManager->get($name);
    }

}