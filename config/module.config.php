<?php
require_once(__DIR__ . '/../Configurator.php');
$configurator = new \ZgApp\Configurator();
return $configurator->setModuleLayout('main')->autoLoadConfig();
