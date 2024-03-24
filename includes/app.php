<?php

use Dotenv\Dotenv;
use Model\UpTask;
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
require 'funciones.php';
require 'database.php';

//Conectarnos a la Base de Datos por funcion de mysqli
$db = conectarDB();

//Conectarnos a la BD usando POO y Mysqli
UpTask::setDB($db);