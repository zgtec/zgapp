<?php

namespace ZgApp\Model;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\HttpClient
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class HttpClient extends \Zend\Http\Client
{
    /**
     * HttpClient constructor.
     *
     * @param null $uri
     * @param null $options
     */
    public function __construct($uri = null, $options = null)
    {
        parent::__construct($uri, array('sslcapath' => '/etc/ssl/certs', 'sslverifypeer' => false, 'keepalive' => true, 'timeout' => 30));
        $this->setAdapter('Zend\Http\Client\Adapter\Socket');
        $this->setMethod('POST');
        return $this;
    }


}
