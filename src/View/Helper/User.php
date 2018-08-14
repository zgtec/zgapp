<?php

/**
 * User helper - allows authorized user to be used in view
 */

namespace ZgApp\View\Helper;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\View\Helper\User
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class User extends AbstractHelper
{
    /**
     * Function __invoke
     *
     *
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->container->get("auth")->getIdentity();
    }
}