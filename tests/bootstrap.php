<?php

$path = getcwd() . DIRECTORY_SEPARATOR .'..'.DIRECTORY_SEPARATOR .'src';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
/**
 * Я хочу верить что PSR-0
 * Реализован внутри исходного приложения
 */
include_once 'Travel/Route.php';
include_once 'Travel/TaskWrapper.php';
include_once 'Travel/BadPriceException.php';