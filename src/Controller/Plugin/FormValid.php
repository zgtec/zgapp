<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * VormValid Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\FormValid
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class FormValid extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param $form
     * @param $csrf
     * @return bool
     */
    public function __invoke($form, $csrf)
    {
        $controller = $this->getController();

        if ($controller->getRequest()->isPost()) {
            $data = array_merge_recursive($controller->getRequest()->getPost()->toArray(), $controller->getRequest()->getFiles()->toArray());

            if (isset($data[$csrf])) {
                $form->setData($data);
                if ($form->isValid()) {
                    return true;
                } else {
                    $form->populateValues($form->getData());
                    return false;
                }
            }
        }
        return false;
    }

}