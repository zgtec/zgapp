<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
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
