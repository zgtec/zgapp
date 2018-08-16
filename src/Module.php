<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp;

use Zend\EventManager\StaticEventManager;

/**
 *
 * Class ZgApp\Module
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Module
{

    /**
     * Function onBootstrap
     *
     *
     *
     * @param $e
     */
    public function onBootstrap($e)
    {
        // Setting php logs be logged to project directory
        ini_set("log_errors", "on");
        ini_set("error_log", __DIR__ . "/../../data/logs/php.log");

        // Setting per-module layout specified in config "module_layouts" array with key "Modulename"
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 100);

        // Attaching preDispatch function to controller
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function ($e) {
            $controller = $e->getTarget();
            if (method_exists($controller, 'preDispatch')) {
                $controller->e = $e;
                $controller->preDispatch();
            }
        }, 100);

        // Attaching postDispatch function to controller
        $app = $e->getApplication();
        $sm = $app->getServiceManager();
        $eventManager = $app->getEventManager();

        $eventManager->getSharedManager()->attach('Zend\Mvc\Application', 'finish', array($this, 'postDispatch'), 1002);


        // Disabling layout for error and exception pages: 
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, function ($e) {
            $result = $e->getResult();
            $result->setTerminal(TRUE);
        });

        // Attaching Post Filtering
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function ($e) {
            $this->filterControllerPost($e);
        }, 101);

    }

    /**
     * Function postDispatch
     *
     *
     *
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function postDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $response = $e->getResponse();
        $content = $response->getContent();
        $config = $e->getApplication()->getServiceManager()->get('config');

        // Replacing keywords with content
        if (null !== $e->getRouteMatch() && is_object($e->getRouteMatch()) && !stristr($e->getRouteMatch()->getParam('controller'), 'Admin\Controller')) {
            $replace = $config['replace'];
            if (is_array($replace)) {
                foreach ($replace as $key => $val) {
                    $content = str_replace($key, $val, $content);
                }
            }
        }


        /*
        if ($config['ows']['debug']) {
            $cache = $e->getApplication()->getServiceManager()->get('cacheFile');
            $cacheName = explode("\\", $e->getRouteMatch()->getParam('controller'));
            $cacheName = "lastPage_".$cacheName[0].end($cacheName).  ucfirst($e->getRouteMatch()->getParam('action'));
            $cache->removeItem($cacheName);
            $cache->addItem($cacheName, $content);
        }
        */

        $response->setContent($content);
        if (!$response instanceof \Zend\Console\Response) {
            $response->getHeaders()->addHeaderLine('X-Frame-Options', 'DENY');
            $response->getHeaders()->addHeaderLine('X-Frame-Options', 'ALLOW-FROM https://americanrailwayexplorer.com/');
        }
    }

    // Filtering POST variables with \Core\Filter\Format filter

    /**
     * Function filterControllerPost
     *
     *
     *
     * @param $e
     */
    protected function filterControllerPost($e)
    {
        $controller = $e->getTarget();
        $request = $controller->getRequest();

        if (!$request instanceof \Zend\Console\Request && ($request->isPost() || 1)) {
            $post = $request->getPost();
            $filteredPost = array();
            $format = new \ZgApp\Filter\Format();
            foreach ($post as $key => $val) {
                if (is_array($val))
                    $filteredPost[$key] = $val;
                else
                    $filteredPost[$key] = $format->filter($val);
            }
            $post->fromArray($filteredPost);
            $request->setPost($post);
        }
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
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Function getAutoloaderConfig
     *
     *
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
