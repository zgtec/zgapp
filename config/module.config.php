<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

require_once(__DIR__ . '/../Configurator.php');

$env = getenv('APPLICATION_ENV') ?: 'production';
$debug = ($env != 'production') ? true : false;

$configurator = new \ZgApp\Configurator();
$config = $configurator->autoLoadConfig();

$modulesPath = realpath(__DIR__ . '/../../../../module');
$modulesDirectories = $configurator->file->listDirs($modulesPath);
foreach ($modulesDirectories as $moduleName) {
    $moduleConfig = (\Zend\Config\Factory::fromFile($modulesPath . '/' . $moduleName . '/config/module.config.php'));
    if (isset($moduleConfig['configurator'])) {
        $mc = $moduleConfig['configurator'];
        $mconfigurator[$moduleName] = new \ZgApp\Configurator($moduleName, $modulesPath . '/' . $moduleName, $config);
        if (isset($mc['route'])) {
            $mconfigurator[$moduleName]->setRoute($mc['route']);
        }
        if (isset($mc['layout'])) {
            $mconfigurator[$moduleName]->setModuleLayout($mc['layout']);
        }
        $config = $mconfigurator[$moduleName]->debug($debug)->autoLoadConfig();
    }
}


return $config;
