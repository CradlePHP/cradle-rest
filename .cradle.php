<?php //-->
/**
 * This file is part of a Custom Package.
 */
require_once __DIR__ . '/package/events.php';
require_once __DIR__ . '/src/events.php';
require_once __DIR__ . '/src/controller.php';
require_once __DIR__ . '/src/App/events.php';
require_once __DIR__ . '/src/App/controller.php';
require_once __DIR__ . '/package/helpers.php';

//bootstrap
$this->preprocess(include __DIR__ . '/src/helpers.php');