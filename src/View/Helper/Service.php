<?php

/**
 * Service helper - allows services to be used in view
 */

namespace ZgApp\View\Helper;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\View\Helper\Service
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Service extends AbstractHelper
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $name
     * @return mixed
     */
    public function __invoke($name)
    {
        return $this->container->get($name);
    }

}