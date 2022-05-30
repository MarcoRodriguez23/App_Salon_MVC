<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
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
         $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has Creado tu cuenta en App Salón, solo debes confirmarla presionando el siguiente enlace</p>";
         $contenido .= "<p>Presiona aquí: <a href='https://serene-river-49406.herokuapp.com/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";        
         $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
         $contenido .= '</html>';
         $mail->Body = $contenido;

         $mail->Body = $contenido;
         $mail->AltBody = "texto alternativo";

         //Enviar el mail
         $mail->send();

    }

    public function enviarInstrucciones() {

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
       $mail->Subject='Reestablece tu password';
       //habilitar html
       $mail->isHTML(true);
       $mail->CharSet = 'UTF-8';

       $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has solicitado reestablecer tu password, sigue el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='https://serene-river-49406.herokuapp.com/recuperar?token=" . $this->token . "'>Reestablecer password</a>";        
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= '</html>';
        $mail->Body = $contenido;

        $mail->Body = $contenido;
        $mail->AltBody = "texto alternativo";

        //Enviar el mail
        $mail->send();

   }

}