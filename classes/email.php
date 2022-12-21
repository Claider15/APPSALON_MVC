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

    public function enviarConfimacion() {

        // Crear el objeto de email

        // Ajustes del servidor
        $mail = new PHPMailer();
        $mail->isSMTP(); // Protocolo de envío de emails
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '43c2b36ed66fc3';
        $mail->Password = '813ad580a7d579';

        // Destinatarios
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');

        // Set HTML
        $mail->isHTML(true); // Le decimos que vamos a utilizar HTML en el cuerpo del email
        $mail->CharSet = 'UTF-8';  

        // Contenido
        $mail->Subject = 'Confirma tu cuenta';
        
        $contenido = "<html>";
        $contenido .= "<p><strong>\"Hola " . $this->nombre . "</strong> Has creado tu cuenta en AppSalon,
        solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar-cuenta?token="
        . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email

        // Ajustes del servidor
        $mail = new PHPMailer();
        $mail->isSMTP(); // Protocolo de envío de emails
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '43c2b36ed66fc3';
        $mail->Password = '813ad580a7d579';

        // Destinatarios
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');

        // Set HTML
        $mail->isHTML(true); // Le decimos que vamos a utilizar HTML en el cuerpo del email
        $mail->CharSet = 'UTF-8';  

        // Contenido
        $mail->Subject = 'Reestablece tu password';
        
        $contenido = "<html>";
        $contenido .= "<p><strong>\"Hola " . $this->nombre . "</strong>. Has solicitado reestablecer 
        tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/recuperar?token="
        . $this->token . "'>Reestablecer password</a> </p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }
}