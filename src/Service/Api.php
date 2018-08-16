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
 * Class ZgApp\Service\Api
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Api implements \Zend\ServiceManager\Factory\FactoryInterface
{

    protected $controller;
    protected $settings;
    protected $serviceManager;

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return $this
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $this->settings = $config['api'];
        $this->serviceManager = $container;
        return $this;
    }

    /**
     * Function setController
     *
     *
     *
     * @param $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Function request
     *
     *
     *
     * @param $name
     * @param $vars
     * @return bool|mixed
     * @throws \Exception
     */
    public function request($name, $vars)
    {
        if (is_null($this->controller))
            throw new \Exception('Controller was not setup');

        $vars['apiname'] = $name;
        $cname = md5($_SERVER['HTTP_HOST'] . '-' . $name . md5(serialize($vars)));

        $response = $this->controller->cacheFile()->getItem($cname);
        try {
            if ($response === false || !is_array($response)) {
                $client = new \ZgApp\Model\HttpClient($this->settings['db']);
                $client->setParameterPost($vars);
                $response = $client->send();
                $response = $response->getBody();
                $response = (new \ZgApp\Filter\Unserialize())->filter(base64_decode($response));
                $this->controller->cacheFile()->addItem($cname, $response);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $response;
    }

    public function cleancache()
    {
        if (is_null($this->controller))
            throw new \Exception('Controller was not setup');

        $this->controller->cacheFile()->flush();
    }

}
