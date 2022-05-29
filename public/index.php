<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\APIController;
use MVC\Router;
USE Controllers\LoginController;
USE Controllers\CitaController;
use Controllers\AdminController;

$router = new Router();

//iniciar Sesion
$router->get('/',[LoginController::class,'login']);
$router->post('/',[LoginController::class,'login']);
//cerrar sesión
$router->get('/logout',[LoginController::class,'logout']);

//recuperar password
$router->get('/olvide',[LoginController::class,'olvide']);  
$router->post('/olvide',[LoginController::class,'olvide']);
$router->get('/recuperar',[LoginController::class,'recuperar']);   
$router->post('/recuperar',[LoginController::class,'recuperar']);

//crear cuenta
$router->get('/crear-cuenta',[LoginController::class,'crear']);  
$router->post('/crear-cuenta',[LoginController::class,'crear']);

//confirmar cuenta
$router->get('/confirmar-cuenta',[LoginController::class,'confirmar']);
$router->get('/mensaje',[LoginController::class,'mensaje']);

//AREA PRIVADA
$router->get('/cita',[CitaController::class,'index']);
$router->get('/admin',[AdminController::class,'index']);

//API DE CITAS
$router->get('/api/servicios',[APIController::class, 'index']);
$router->post('/api/citas',[APIController::class, 'guardar']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();