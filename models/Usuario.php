<?php 

namespace Model;

class Usuario extends ActiveRecord{
    //tabla de la base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','apellido','email','password','telefono','admin','confirmado','token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args=[])
    {
        $this->id=$args['id']??null;
        $this->nombre=$args['nombre']??'';
        $this->apellido=$args['apellido']??'';
        $this->email=$args['email']??'';
        $this->password=$args['password']??'';
        $this->telefono=$args['telefono']??'';
        $this->admin=$args['admin']??'0';
        $this->confirmado=$args['confirmado']??'0';
        $this->token=$args['token']??'';
    }

    //mensajes de validacion
    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error']['nombre']='El nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error']['apellido']='El apellido es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error']['email']='El email es obligatorio';
        }
        if(!$this->telefono){
            self::$alertas['error']['telefono']='El telefono es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error']['password']='El password es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error']['passwordExtension']='El password debe tener mínimo 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error']['email']='El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error']['password']='El password es obligatorio';
        }
        if(strlen($this->password)<6){
            self::$alertas['error']['passwordExtension']='El password debe tener mínimo 6 caracteres';
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error']['email']='El email es obligatorio';
        }

        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = "El password es obligatorio";
        }
        if(strlen($this->password)<6){
            self::$alertas['error'][] = "El password debe tener minimo 6 caracteres";
        }

        
        return self::$alertas;
    }

   // Revisa si el usuario ya existe
   public function existeUsuario() {
    $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

    $resultado = self::$db->query($query);

    if($resultado->num_rows) {
        self::$alertas['error']['yaExiste'] = 'El Usuario ya esta registrado';
    }

    return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        //generando un id de 13 tanto numeros como letras
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($auth){
        
        $resultado = password_verify($auth->password,$this->password);
        
        if(!$this->confirmado || !$resultado){
            // debuguear("el usuario no esta confirmado");
            self::$alertas['error']['sinConfirmar'] = "Password Incorrecto o tu cuenta no esta confirmada";
        }
        else{
            return true;
        }
    }

    

}