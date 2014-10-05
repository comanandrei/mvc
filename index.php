<?php
/**
 * Created by PhpStorm.
 * User: Andrei
 * Date: 06.08.2014
 * Time: 22:47
 */


namespace Cool;

define('BASE_URL', '');

include_once BASE_URL . 'system/core/App.php';
/** @var system\core\App$app */
$app = system\core\App::instance();
$app->execute();
