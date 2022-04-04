<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

         //crear una instancia de PHPMailer
        $mail = new PHPMailer();

        //Configurando SMTP
        $mail->isSMTP();
        $mail->Host='smtp.mailtrap.io';
        $mail->SMTPAuth= true;
        $mail->Username='f8f5444957e76d';
        $mail->Password='0642cad10af248';
        $mail->SMTPSecure='tls';
        $mail->Port='2525';

        //configurando el contenido  del Email
        //quien lo envia
        $mail->setFrom('cuentas@appsalon.com');
        //A donde va
        $mail->addAddress('cuentas@appsalon.com','AppSalon.com');
        $mail->Subject='Confirma tu cuenta';
        //habilitar html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
         $contenido .= "<p><strong>Hola " . $this->email .  "</strong> Has Creado tu cuenta en App Salón, solo debes confirmarla presionando el siguiente enlace</p>";
         $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";        
         $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;

         $mail->Body = $contenido;
         $mail->AltBody = "texto alternativo";

         //Enviar el mail
         $mail->send();

    }

}