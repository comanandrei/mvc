<?php

$database = array();
$database['db']['driver'] = 'mysql';
$database['db']['server'] = 'localhost';
$database['db']['port'] = '3306';
$database['db']['user'] = 'root';
$database['db']['password'] = '';
$database['db']['database'] = '';
//TODO: ??? next line
$database['db']['options'] = array(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return $database;


