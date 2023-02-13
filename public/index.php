<?php
session_start();
// session_destroy();
require '../vendor/autoload.php';
require '../src/routes.php';

$router->run($router->routes);
