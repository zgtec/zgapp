<?php

namespace ZgApp\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Bootstrap
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Bootstrap extends AbstractActionController
{

    public $view;
    public $headTitle = false;
    public $mainconfig;
    public $module;
    public $controller;
    public $action;
    public $session;
    public $subdomain = false;
    protected $container;

    public function preDispatch()
    {
        /** Init Application Config * */
        $this->layout()->mainconfig = $this->view['mainconfig'] = $this->mainconfig = $this->getConfig();
        $this->layout()->version = $this->view['version'] = $this->mainconfig['version'];

        /** Setting Module/Controller/Action variables * */
        $module = explode("\\", $this->getEvent()->getRouteMatch()->getParam('controller'));
        $this->module = array_shift($module);

        $controller = explode("\\", $this->getEvent()->getRouteMatch()->getParam('controller'));
        $this->controller = $this->view['controller'] = $this->layout()->controllername = strtolower(isset($controller[2]) ? $controller[2] : 'index');
        $this->action = $this->layout()->action = $this->getEvent()->getRouteMatch()->getParam('action');

        $request = $this->getRequest();
        if (!$request instanceof \Zend\Console\Request) {
            /** Force HTTPS * */
            $this->forceHttps();

            /** Init Session * */
            $this->service('sessionManager');
            $this->session = $this->session("Session" . $this->module);
            $this->messenger = $this->flashMessenger();

            /** Loading Common Services * */
            $this->eventlog = $this->service('logEvent');
        }
    }

    public function postDispatch()
    {
        // echo "<pre>";
        // print_r($this->mainconfig['replace']);
        // exit;

    }

    /**
     * Function getContainer
     *
     *
     *
     * @return mixed
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Function setContainer
     *
     *
     *
     * @param $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * Function getConfig
     *
     *
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->getContainer()->get('config');
    }

    /**
     * Function setSessionLifetime
     *
     *
     *
     * @param int $time
     */
    public function setSessionLifetime($time = 36000)
    {
        $config = $this->getConfig();
        $config['session']['cookie_lifetime'] = $time;
        unset($config['session']["save_path"]);
        $sessionConfig = new \Zend\Session\Config\SessionConfig();
        $sessionConfig->setOptions($config['session']);
        $this->service('sessionManager')->setConfig($sessionConfig);
    }

}
