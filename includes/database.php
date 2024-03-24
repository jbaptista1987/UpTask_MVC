<?php

function conectarDB() : mysqli { 
    try {
      $conexionBD = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
      $conexionBD->set_charset("utf8");
      return $conexionBD;
    }
    catch (Exception $e)
      {
        echo 'Conexion a BD: ',  $e->getMessage();
        exit;
      }
}