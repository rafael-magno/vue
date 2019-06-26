<?php

session_start();

require_once 'model/Model.php';
require_once 'controller/Controller.php';
require_once 'helpers.php';
require_once 'constantes.php';

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);

$classe = $uri_segments[3];
$metodo = $uri_segments[4];

$classe .= 'Controller';

require_once "controller/$classe.php";

$obj = new $classe();

if (!$metodo)
{
    $metodo = 'index';
}

$obj->$metodo();