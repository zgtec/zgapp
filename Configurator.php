<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * View Manager autoloader - should be included to module.config.php
 */

namespace ZgApp;

/**
 *
 * Class ZgApp\Configurator
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Configurator
{

    public $file;
    protected $moduleName;
    protected $dir;
    protected $config = array();

    /**
     * Configurator constructor.
     */
    public function __construct($moduleName = false, $dir = false, $config = array())
    {
        $debug = debug_backtrace();
        $trace = array_shift($debug);
        $this->file = new \ZgApp\Controller\Plugin\File();
        $this->moduleName = ($moduleName) ? $moduleName : basename(dirname(dirname($trace['file'])));
        if ($this->moduleName == 'zgapp') {
            $this->moduleName = 'ZgApp';
        }
        $this->dir = ($dir) ? $dir : dirname(dirname($trace['file']));
        $this->config = $config;
        $this->setAclRoles();
    }

    /**
     * Setting Default Acl Roles
     *
     *
     *
     * @return $this
     */
    public function setAclRoles()
    {
        $this->config["acl"]["roles"] = ["guest" => "", "user" => "guest", "admin" => "user"];
        return $this;
    }


    /**
     * Function setModuleLayout
     *
     *
     *
     * @param $name
     * @param bool $moduleName
     * @return $this
     */
    public function setModuleLayout($name, $moduleName = false)
    {
        if (!$moduleName) {
            $moduleName = $this->moduleName;
        }
        $this->config['module_layouts'][$this->moduleName] = strtolower($moduleName) . '/layout/' . $name;
        return $this;
    }

    /**
     * Function setExtensionRoute
     *
     *
     *
     * @param $extension
     * @return $this
     */
    public function setExtensionRoute($extension)
    {
        $this->config['router']['routes']['extension_' . $extension] = array(
            'type' => 'Zend\Mvc\Router\Http\Regex',
            'options' => array(
                'regex' => '/(?<page>[a-zA-Z0-9\\/_-]+).' . $extension . '?',
                'defaults' => array(
                    'controller' => $this->moduleName . '\Controller\\' . ucfirst($extension),
                    'action' => 'index',
                ),
                'spec' => '/%page%.' . $extension,
            ),
        );
        return $this;
    }

    /**
     * Function setRoute
     *
     *
     *
     * @param $name
     * @return $this
     */
    public function setRoute($name)
    {
        if ($name == '/' || $name == '') {
            $this->config['router']['routes']['homeapp'] = array(
                'type' => 'Zend\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => $this->moduleName . '\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            );

            $src = $this->dir . "/src/" . $this->moduleName;
            $src = str_replace('/src/ZgApp', '/src', $src);
            $controllers = $this->file->listFiles($src . '/Controller', 'Controller.php');

            foreach ($controllers as $controller) {
                if (in_array($controller, ['Abstract'])) {
                    continue;
                }
                $this->config['router']['routes'][strtolower($this->moduleName . '-' . $controller)] = array(
                    'type' => 'segment',
                    'options' => array(
                        'route' => '/' . strtolower($controller) . '[/:action][/:id][/:k]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'k' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults' => array(
                            '__NAMESPACE__' => $this->moduleName . '\Controller',
                            'controller' => strtolower($controller),
                            'action' => 'index'
                        ),
                    ),
                    'may_terminate' => false,
                );
            }


        } else {
            $this->config['router']['routes'][strtolower($this->moduleName)] = array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/' . $name . '[/:controller[/:action][/:id]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        // add the default namespace for :controllers in this route
                        '__NAMESPACE__' => $this->moduleName . '\Controller',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => $this->moduleName . '\Controller',
                        'controller' => $this->moduleName . '\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => false,
            );
        }

        return $this;
    }

    /**
     * Function getConfig
     *
     *
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Function autoLoadConfig
     *
     *
     *
     * @return array
     */
    public function autoLoadConfig()
    {
        $src = $this->dir . "/src/" . $this->moduleName;
        $src = str_replace('/src/ZgApp', '/src', $src);

        $this->setTemplatePathStack();
        $this->setTemplateMap($this->file->listFilesRecursive($this->dir . "/view", '.phtml'));
        $this->setControllers($this->file->listFiles($src . '/Controller', 'Controller.php'));
        $this->setControllerPlugins($this->file->listFiles($src . '/Controller/Plugin'));
        $this->setServices($this->file->listFiles($src . '/Service'));
        $this->setViewHelpers($this->file->listFiles($src . '/View/Helper'));

        return $this->getConfig();
    }

    /**
     * Function setControllers
     *
     *
     *
     * @param array $controllers
     * @return $this
     */
    public function setControllers(array $controllers)
    {
        foreach ($controllers as $name) {
            $name = str_replace(".php", "", $name);
            $this->config['controllers']['factories'][$this->moduleName . '\Controller\\' . $name] = 'ZgApp\Controller\Factory\AbstractFactory';
            $this->config["acl"]["resources"][] = $this->moduleName . '\Controller\\' . $name;
        }
        return $this;
    }

    /**
     * Function setControllerPlugins
     *
     *
     *
     * @param array $plugins
     * @return $this
     */
    public function setControllerPlugins(array $plugins)
    {
        foreach ($plugins as $name) {
            $this->config['controller_plugins']['invokables'][lcfirst($name)] = $this->moduleName . '\Controller\Plugin\\' . $name;
        }
        return $this;
    }

    /**
     * Function setServices
     *
     *
     *
     * @param array $services
     * @return $this
     */
    public function setServices(array $services)
    {
        $this->config['service_manager']['abstract_factories'] = array(
            'Zend\Navigation\Service\NavigationAbstractServiceFactory'
        );
        foreach ($services as $name) {
            $this->config['service_manager']['factories'][lcfirst($name)] = $this->moduleName . '\Service\\' . $name;
        }
        return $this;
    }

    /**
     * Function setViewHelpers
     *
     *
     *
     * @param array $helpers
     * @return $this
     */
    public function setViewHelpers(array $helpers)
    {
        foreach ($helpers as $name) {
            $this->config['view_helpers']['factories'][lcfirst($name)] = $this->moduleName . "\View\Helper\Factory\\" . ucfirst($name);
        }
        return $this;
    }

    /**
     * Function setTemplatePathStack
     *
     *
     *
     */
    public function setTemplatePathStack()
    {
        $this->config['view_manager']['doctype'] = 'HTML5';
        $this->config['view_manager']['strategies'] = ['ViewJsonStrategy'];
        $this->config['view_manager']['not_found_template'] = 'error/404';
        $this->config['view_manager']['exception_template'] = 'error/index';
        $this->config['view_manager']['template_path_stack'][strtolower($this->moduleName)] = $this->dir . '/view';
        if (file_exists($this->dir . '/view/error/404.phtml')) {
            //$this->config['view_manager']['template_map']['error/404'] = $this->dir . '/view/error/404.phtml';
        }
        if (file_exists($this->dir . '/view/error/index.phtml')) {
            //$this->config['view_manager']['template_map']['error/index'] = $this->dir . '/view/error/index.phtml';
        }

    }

    /**
     * Function debug
     *
     *
     *
     * @param bool $debug
     * @return $this
     */
    public function debug($debug = false)
    {
        if ($debug) {
            $this->config['view_manager']['display_not_found_reason'] = true;
            $this->config['view_manager']['display_exceptions'] = true;
        }
        return $this;
    }

    /**
     * Function setTemplateMap
     *
     *
     *
     * @param $templates
     * @return $this
     */
    public function setTemplateMap($templates)
    {
        foreach ($templates as $name) {
            $this->config['view_manager']['template_map'][strtolower($this->moduleName) . $name] = $this->dir . '/view' . $name . '.phtml';
        }
        return $this;
    }


}
