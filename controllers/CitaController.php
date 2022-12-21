<?php

namespace Controllers;

use MVC\Router;
use Model\ActiveRecord;

class CitaController extends ActiveRecord {
    public static function index(Router $router) {

        session_start();

        isAuth();
        
        $router->render('cita/index', [
            'nombre' => $_SESSION['nombre'], // para que 'nombre' sea igual a $nombre
            'id' => $_SESSION['id'] // para que estas variables estén disponibles en la vista (index.php)
        ]);
    }
}