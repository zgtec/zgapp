<?php
require_once(__DIR__ . '/../Configurator.php');
$configurator = new \ZgApp\Configurator();
$config = $configurator->setModuleLayout('main')->autoLoadConfig();
return $config;
