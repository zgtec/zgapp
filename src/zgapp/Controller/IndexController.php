<?php

namespace ZgApp\Controller;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\IndexController
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class IndexController extends LayoutController
{
    public $headTitle = 'Module Home';

    /**
     * Function indexAction
     *
     *
     *
     * @return mixed
     */
    public function indexAction()
    {
        return $this->getView();
    }
}
