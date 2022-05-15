<?php

namespace Model;

class Servicio extends ActiveRecord{
    //datos para hacer referencia a la base de datos
    protected static $tabla = 'servicios';
    protected static $columnasBD = ['id','nombre','precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args['id']??null;
        $this->nombre = $args['nombre']??'';
        $this->precio = $args['precio']??'';
    }

}