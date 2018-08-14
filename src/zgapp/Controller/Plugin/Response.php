<?php

/**
 * Response Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Response
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Response extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param string $content
     * @param int $status
     * @return mixed
     */
    public function __invoke($content = '', $status = 200)
    {
        $response = $this->getController()->getResponse();
        $response->setStatusCode($status);
        $response->setContent($content);
        return $response;
    }

}