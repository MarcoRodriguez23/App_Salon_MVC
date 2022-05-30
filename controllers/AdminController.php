<?php

namespace Controllers;

use Model\AdminCita;
require_once '../models/AdminCita.php';
use MVC\Router;
require_once '../Router.php';

class AdminController {
    public static function index(Router $router){
        session_start();

        isAdmin();
        
        $nombre = $_SESSION['nombre'];
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fechas = explode('-',$fecha);
        // debuguear($fechas);

        if(checkdate($fechas[1],$fechas[2],$fechas[3])){
            header('Location: /404');
        }

        //QUERY
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '${fecha}' ";
        $citas = AdminCita::SQL($consulta);

        // debuguear($citas);
        
        $router->render('admin/index',[
            'nombre'=>$nombre,
            'citas'=>$citas,
            'fecha'=>$fecha
        ]);

    }
}