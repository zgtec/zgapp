<?php

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Service\Mailer
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Mailer extends \Zend\Mail\Message implements \Zend\ServiceManager\Factory\FactoryInterface
{

    protected $_options;

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return \ZgApp\Model\Mail
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $mail = new \ZgApp\Model\Mail($config['mailer']);
        return $mail;
    }


}
