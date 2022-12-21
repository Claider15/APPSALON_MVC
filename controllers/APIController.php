<?php

namespace Controllers;

use MVC\Router;
use Model\Servicio;
use Model\Cita;
use Model\CitaServicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar() {
        // $respuesta = [
        //     'datos' => $_POST
        // ];

        // Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $idCita = $resultado['id'];

        // Almacena los servicios con el id de la cita
        $idServicios = explode(',', $_POST['servicios']);
        
        foreach ($idServicios as $idServicio) {
            $args = [
                'citaid' => $idCita,
                'servicioid' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        // Retornamos una respuesta
        $respuesta = [
            'resultado' => $resultado // para conectar con el archivo de js (if resultado.resultado)
        ];

        // $respuesta = [
        //     'cita' => $cita // para que se pueda ver en la consola la estructura del objeto de cita
        // ];

        echo json_encode($respuesta);
    }

    public static function eliminar() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER["HTTP_REFERER"]);
        }
    }
}