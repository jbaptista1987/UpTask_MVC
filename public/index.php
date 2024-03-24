<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\loginControllers;
use Controllers\dashboardControllers;
use Controllers\tareasControllers;

$router = new Router();

//------------********************MANEJO DE SESION****************---------------------------------//
//Iniciar y Cerrar Sesion
$router->get('/', [loginControllers::class, 'login']);
$router->post('/', [loginControllers::class, 'login']);
$router->get('/logout', [loginControllers::class, 'logout']);

//Recuperar Password
$router->get('/olvidarpass', [loginControllers::class, 'olvidarpass']);
$router->post('/olvidarpass', [loginControllers::class, 'olvidarpass']);
$router->get('/recuperarpass', [loginControllers::class, 'recuperarpass']);
$router->post('/recuperarpass', [loginControllers::class, 'recuperarpass']);

//Crear Cuenta
$router->get('/crearcta', [loginControllers::class, 'crearcta']);
$router->post('/crearcta', [loginControllers::class, 'crearcta']);

//Confirmar Cuenta
$router->get('/confirmarcta', [loginControllers::class, 'confirmarcta']);

//CRUD Usuarios
$router->get('/crudusuarios', [loginControllers::class, 'crudusuarios']);
$router->post('/crudusuarios', [loginControllers::class, 'crudusuarios']);

//
$router->get('/clavenueva', [loginControllers::class, 'claveNueva']);
$router->post('/clavenueva', [loginControllers::class, 'claveNueva']);

//------------********************DASHBOARD***************---------------------------------//
$router->get('/panelprincipal', [dashboardControllers::class, 'principal']);
$router->post('/panelprincipal', [dashboardControllers::class, 'principal']);

$router->get('/proyecto', [dashboardControllers::class, 'proyecto']);
$router->post('/proyecto', [dashboardControllers::class, 'proyecto']);

$router->get('/crear_proyectos', [dashboardControllers::class, 'crear_proyectos']);
$router->post('/crear_proyectos', [dashboardControllers::class, 'crear_proyectos']);

$router->get('/perfil', [dashboardControllers::class, 'perfil']);
$router->post('/perfil', [dashboardControllers::class, 'perfil']);


//---------***********FETCH API PARA LAS TAREAS***************-----------------------//
$router->get('/api/tareas', [tareasControllers::class, 'indexTareas']);
$router->post('/api/tareas', [tareasControllers::class, 'crearTareas']);
$router->post('/api/tareas/actualizar', [tareasControllers::class, 'actualizarTareas']);
$router->post('/api/tareas/eliminar', [tareasControllers::class, 'eliminarTareas']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();