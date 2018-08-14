<?php

/**
 * View Manager autoloader - should be included to module.config.php
 */

namespace ZgApp;

class Configurator
{

    protected $moduleName;
    protected $dir;
    protected $file;
    protected $config = array();

    public function __construct()
    {
        $debug = debug_backtrace();
        $trace = array_shift($debug);
        $this->moduleName = basename(dirname(dirname($trace['file'])));
        $this->dir = dirname(dirname($trace['file']));
        $this->file = new \ZgApp\Controller\Plugin\File();
    }

    public function setModuleLayout($name, $moduleName = false)
    {
        if (!$moduleName) {
            $moduleName = $this->moduleName;
        }
        $this->config['module_layouts'][$this->moduleName] = strtolower($moduleName) . '/layout/' . $name;
        return $this;
    }

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

    public function setRoute($name)
    {
        if ($name == '/') {
            $this->config['router']['routes']['home'] = array(
                'type' => 'Zend\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => $this->moduleName . '\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            );
            $this->config['router']['routes'][strtolower($this->moduleName)] = array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:controller][/:action][/:id][/:k]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'k' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => $this->moduleName . '\Controller',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Wildcard',
                        'options' => array(),
                    ),
                ),
            );
        } else {
            $this->config['router']['routes'] = array(
                strtolower($this->moduleName) => array(
                    'type' => 'Literal',
                    'options' => array(
                        'route' => '/' . $name,
                        'defaults' => array(
                            'controller' => $this->moduleName . '\Controller\Index',
                            'action' => 'index',
                        ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                        'default' => array(
                            'type' => 'Segment',
                            'options' => array(
                                'route' => '/[:controller[/:action][/:id]]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                    // add the default namespace for :controllers in this route
                                    '__NAMESPACE__' => $this->moduleName . '\Controller',
                                ),
                            ),
                        ),
                    ),
                ),
            );
        }

        return $this;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function autoLoadConfig()
    {
        $src = $this->dir . "/src/" . $this->moduleName;

        $this->setTemplatePathStack();
        $this->setTemplateMap($this->file->listFilesRecursive($this->dir . "/view", '.phtml'));
        $this->setControllers($this->file->listFiles($src . '/Controller', 'Controller.php'));
        $this->setControllerPlugins($this->file->listFiles($src . '/Controller/Plugin'));
        $this->setServices($this->file->listFiles($src . '/Service'));
        $this->setViewHelpers($this->file->listFiles($src . '/View/Helper'));

        return $this->getConfig();
    }

    public function setControllers(array $controllers)
    {
        foreach ($controllers as $name) {
            $name = str_replace(".php", "", $name);
            $this->config['controllers']['factories'][$this->moduleName . '\Controller\\' . $name] = 'ZgApp\Controller\Factory\AbstractFactory';
        }
        return $this;
    }

    public function setControllerPlugins(array $plugins)
    {
        foreach ($plugins as $name) {
            $this->config['controller_plugins']['invokables'][lcfirst($name)] = $this->moduleName . '\Controller\Plugin\\' . $name;
        }
        return $this;
    }

    public function setServices(array $services)
    {
        foreach ($services as $name) {
            $this->config['service_manager']['factories'][lcfirst($name)] = $this->moduleName . '\Service\\' . $name;
        }
        return $this;
    }

    public function setViewHelpers(array $helpers)
    {
        foreach ($helpers as $name) {
            /* $this->config['view_helpers']['factories'][lcfirst($name)] = function($pm, $name) {
                 $classname = $this->moduleName . "\View\Helper\\" . ucfirst($name);
                 $viewHelper = new $classname;
                 $viewHelper->setServiceLocator($pm->getServiceLocator());
                 return $viewHelper;
             };
            */
            $this->config['view_helpers']['factories'][lcfirst($name)] = $this->moduleName . "\View\Helper\Factory\\" . ucfirst($name);
        }
        return $this;
    }

    public function setTemplatePathStack()
    {
        $this->config['view_manager']['template_path_stack'] = array(
            strtolower($this->moduleName) => $this->dir . '/view',
        );
        $this->config['view_manager']['strategies'] = array(
            'ViewJsonStrategy',
        );
        $this->config['view_manager']['doctype'] = 'HTML5';
        $this->config['view_manager']['not_found_template'] = 'error/404';
        $this->config['view_manager']['exception_template'] = 'error/index';
        if (file_exists($this->dir . '/../view/error/404.phtml'))
            $this->config['view_manager']['template_map']['error/404'] = $this->dir . '/../view/error/404.phtml';
        if (file_exists($this->dir . '/../view/error/index.phtml'))
            $this->config['view_manager']['template_map']['error/index'] = $this->dir . '/../view/error/index.phtml';
    }

    public function debug($debug = false)
    {
        if ($debug) {
            $this->config['view_manager']['display_not_found_reason'] = true;
            $this->config['view_manager']['display_exceptions'] = true;
        }
        return $this;
    }

    public function setTemplateMap($templates)
    {
        foreach ($templates as $name) {
            $this->config['view_manager']['template_map'][strtolower($this->moduleName) . $name] = $this->dir . '/view' . $name . '.phtml';
        }
        return $this;
    }


}
