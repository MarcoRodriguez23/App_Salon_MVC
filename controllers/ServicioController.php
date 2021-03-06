<?php

namespace Controllers;

use Model\Servicio;
require_once '../models/Servicio.php';
use MVC\Router;
require_once '../Router.php';

class ServicioController {
    public static function index(Router $router){
        session_start();

        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index',[
            'nombre'=>$_SESSION['nombre'],
            'servicios'=>$servicios
        ]);

    }

    public static function crear(Router $router){
        session_start();

        isAdmin();

        $servicio = new Servicio();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==="POST"){
            $servicio->sincronizar($_POST);
            $servicio->precio = intval($servicio->precio);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $resultado=$servicio->guardar();
                if($resultado){
                    header('Location: /servicios');
                }
            }
        }

        $router->render('servicios/crear',[
            'nombre'=>$_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]);
    }

    public static function actualizar(Router $router){
        session_start();

        isAdmin();

        if(!is_numeric($_GET['id'])) return;
        $servicio = Servicio::find($_GET['id']);
        
        if($servicio === null){
            header('Location: /servicios');
        }
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==="POST"){
            $servicio->sincronizar($_POST);
            $servicio->precio = intval($servicio->precio);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $resultado=$servicio->guardar();
                if($resultado){
                    header('Location: /servicios');
                }
            }
        }

        $router->render('servicios/actualizar',[
            'nombre'=>$_SESSION['nombre'],
            'servicio'=>$servicio,
            'alertas'=>$alertas
        ]);
    }

    public static function eliminar(){
        session_start();

        isAdmin();

        if($_SERVER['REQUEST_METHOD']==="POST"){
            $id=$_POST['id'];
            $servicio = Servicio::find($id);

            $servicio->eliminar();

            header('Location: /servicios');
        }

    }
}