<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Service helper - allows services to be used in view
 */

namespace ZgApp\View\Helper;

/**
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