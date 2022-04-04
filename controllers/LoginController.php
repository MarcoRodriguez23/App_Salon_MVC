<?php

namespace Controllers;

use classes\Email;
use Model\Usuario;
use MVC\Router;

require_once '../classes/email.php';

class LoginController{
    public static function login(Router $router){
        $router->render('auth/login',[

        ]);
    }

    public static function logout(){
        echo "desde logout";
    }

    public static function olvide(Router $router){
        $router->render('auth/olvide',[

        ]);
    }
    
    public static function recuperar(){
        echo "desde recuperar";
    }

    public static function crear(Router $router){

        $usuario=new Usuario();

        //alertas vacias
        $alertas = Usuario::getAlertas();
        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validarNuevaCuenta();
            
            if (empty($alertas)) {
                $yaExiste=$usuario->existeUsuario();
                
                if($yaExiste->num_rows){
                    $alertas = Usuario::getAlertas();
                }
                else{
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();
                    // Enviar el Email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //crear el usuario
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }


        $router->render('auth/crear-cuenta',[
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }

    public static function mensaje(Router $router){

        $router->render('auth/mensaje',[

        ]);
    }

    public static function confirmar(Router $router){
        $alertas = [];

        //recogiendo el token por GET
        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token',$token);
        
        if(empty($usuario)){
            //mostrar mensaje
            Usuario::setAlerta("error","token no válido");
        }
        else{
            //confirmando la existencia del usuario
            $usuario->confirmado = 1;
            //eliminando el token del usuario que ya fue confirmado
            $usuario->token = null;
            //actualizando el usuario ya sin token y confirmado
            $usuario->guardar();
            Usuario::setAlerta("exito","Token válido, confirmando usuario");
        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas'=>$alertas
        ]);
    }
}