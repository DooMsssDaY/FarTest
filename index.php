<?php

// корень сайта
define(ROOT, dirname(__FILE__));
// подключение автозагрузчика
require_once(ROOT.'/components/Autoload.php');

// вывод ошибок
// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

session_start();

// запуск обработки роутов
$router = new Router();
$router->run();