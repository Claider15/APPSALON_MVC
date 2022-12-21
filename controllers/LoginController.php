<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router) {
        $alertas = [];
        $auth = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                
                if ($usuario) {
                    // Verificar el Password
                    if($usuario->comprobarPasswordAndVerificado($auth)) {
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header("Location: /admin");
                        } else {
                            header("Location: /cita");
                        }

                    };

                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    public static function logout() {
        session_start();
        
        $_SESSION = []; // Para dejar vacío el objeto de $_SESSION 
        
        header("Location: /");
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = $auth->where('email', $auth->email);
                
                if ($usuario && $usuario->confirmado === "1") {
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar el email
                    
                    Usuario::setAlerta('exito', 'Revisa tu email');
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    

                } else {
                    $auth::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        

        $alertas = []; // las alertas en activerecord se van agregando en un arreglo

        $error = false;
        
        $token = sanitizar($_GET['token']); // para acceder a los datos de la url en confirmar-cuenta (?token=)

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token NO Válido');
            $error = true;

        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                $auth = new Usuario($_POST);

                $alertas = $auth->validarPassword();
                
                if (empty($alertas)) {
                    $auth->hashPassword();
                    
                    $usuario->password = $auth->password;
                    $usuario->token = null;
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /');
                    }  
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas, // esas alertas van a incluir si se confirmó correctamente o el token no es válido
            'error' => $error
        ]);
    }

    public static function crear(Router $router) {

        $usuario = new Usuario;   

        // Alertas Vacías
        $alertas =[];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta esté vacío
            if (empty($alertas)) {
                // Verificar que el Usuario no esté registrado;
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();
                    
                    // Generar un Token único
                    $usuario->crearToken();

                    
                    // Crear el Usuario
                    $resultado = $usuario->guardar();

                    //Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfimacion();

                    // quitar espacio a token en BD (solo en crear; problema personal que no pude resolver)
                    if ($resultado) {
                        $resultado = Usuario::where('token', $usuario->token . " ");
                        $resultado->token = $usuario->token;
                        $resultado->guardar();
                        header('Location: /mensaje');
                    }
                }
                
            }

        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router) {
        $alertas = []; // las alertas en activerecord se van agregando en un arreglo

        $token = $_GET['token']; // para acceder a los datos de la url en confirmar-cuenta (?token=)

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            //Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token NO Válido');

        } else {
            // Modificar a Usuario confirmado
            $usuario->confirmado = 1;
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obtener alertas 
        $alertas = Usuario::getAlertas();

        // renderizar las vistas
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas // esas alertas van a incluir si se confirmó correctamente o el token no es válido
        ]);
    }
}