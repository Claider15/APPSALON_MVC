<?php

namespace Model;

class Usuario extends ActiveRecord {

    // Base de datos
    protected static $tabla = 'usuarios'; // tabla donde va a encontrar los datos
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token']; // para normalizarlos datos (iterar sobre los registros e insetarlos en el objeto en memoria)

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) // Construct permite inicializar las propiedades de un objeto cuando se cree un objeto de una clase
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? null;
        $this->apellido = $args['apellido'] ?? null;
        $this->email = $args['email'] ?? null;
        $this->password = $args['password'] ?? null;
        $this->telefono = $args['telefono'] ?? null;
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? "";
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = "El nombre del es obligatorio";
        }

        if (!$this->apellido) {
            self::$alertas['error'][] = "El apellido del es obligatorio";
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = "El teléfono es obligatorio";
        }

        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }

        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
        }   

        return self::$alertas;
    }

    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        return self::$alertas;
    }

    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = "El email es obligatorio";
        }

        return self::$alertas;
    }

    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = "El password es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = "El password debe contener al menos 6 caracteres";
        }
        
        return self::$alertas;
    }

    // Revisa si el Usuario ya existe
    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);
        
        if ($resultado->num_rows) {
            self::$alertas['error'][] = "El Usuario ya está registrado";
        }
        return $resultado;
    }


    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken() {
        $this->token = uniqid();    
    }

    public function comprobarPasswordAndVerificado($auth) {

        $resultado = password_verify($auth->password, $this->password);

        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = "Password incorrecto o tu cuenta no ha sido confirmada";
        }

        return $resultado;

    }

}