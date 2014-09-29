<?php
/****************
 * :string, :num
 **************/

$routes = array();
$routes['default/:string'] = 'ex_controller/index/$1';

return $routes;

