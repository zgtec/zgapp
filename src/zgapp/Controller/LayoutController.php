<?php

namespace ZgApp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\LayoutController
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class LayoutController extends AbstractActionController
{
    public $view;
    public $headTitle = false;

    public function preDispatch()
    {
        $this->layout()->title = 'ZgApp Module';
        $this->headTitle($this->layout()->title);
        $this->layout()->controller = $this->getEvent()->getRouteMatch()->getParam('controller');
        $this->layout()->action = $this->getEvent()->getRouteMatch()->getParam('action');
    }

}

