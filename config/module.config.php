<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

require_once(__DIR__ . '/../Configurator.php');
$configurator = new \ZgApp\Configurator();
$config = $configurator->setModuleLayout('main')->autoLoadConfig();
return $config;
