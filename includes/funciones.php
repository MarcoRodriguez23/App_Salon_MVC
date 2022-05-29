<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//revisar que el usuario esta autenticado
function isAuth():void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function esUltimo(string $actual, string $proximo):bool{

    if($actual !== $proximo){
        return true;
    }
    else return false;
}