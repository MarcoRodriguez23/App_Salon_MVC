<?php

namespace Controllers;

use classes\Email;
use Model\Usuario;
use MVC\Router;

require_once '../classes/email.php';

class LoginController{
    public static function login(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //comprobar que exista el usuario
                $usuario = Usuario::where('email',$auth->email);
                
                if($usuario){
                    //verificar password
                    if($usuario->comprobarPasswordAndVerificado($auth)){
                        //autenticar al usuario
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre." ".$usuario->apellido;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        //redireccionar
                        if($usuario->admin === 1){
                            // echo "es admin";
                            $_SESSION['admin']= $usuario->admin??null;
                            header('Location: /admin');
                        }
                        else{
                            // echo "es cliente";
                            header('Location: /cita');
                        }

                        
                    }
                }
                else{
                    Usuario::setAlerta('error','Usuario no encontrado');
                }
            }

        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/login',[
            "alertas"=>$alertas
        ]);
    }

    public static function logout(){
        echo "desde logout";
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);

            $auth->validarEmail();

            if (empty($alertas)) {

                $usuario = Usuario::where('email',$auth->email);
                
                if($usuario && $usuario->confirmado === "1"){
                    
                    $usuario->Creartoken();
                    $usuario->guardar();

                    //enviar el email
                    $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                    $email->enviarInstrucciones();

                    //alerta de exito
                    Usuario::setAlerta('exito','Revisa tu email para el siguiente paso');
                }
                else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                }
                
            }

        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide',[
            'alertas'=>$alertas
        ]);
    }
    
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        
        //buscar usuario por el token

        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','token no valido');
            $error=true;
        }
        else{
            if($_SERVER['REQUEST_METHOD']==='POST'){
    
                $password = new Usuario($_POST);
                $alertas = $password->validarPassword();
    
                if(empty($alertas)){
                    //quitando la antigua contraseña
                    $usuario->password = null;

                    $usuario->password = $password->password;
                    $usuario->hashPassword();

                    $usuario->token = null;
                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /');
                    }
                }
    
            }
        }


        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar',[
            'alertas'=>$alertas,
            'error'=>$error
        ]);
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