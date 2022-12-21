<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php  
    include __DIR__ . "/../templates/alertas.php"    
?>

<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" placeholder="Tu Nombre" id="nombre" name="nombre" value="<?php echo sanitizar($usuario->nombre) ?>"> <!-- name es para que se pueda seleccionar con $_POST['name'] -->
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" placeholder="Tu Apellido" id="apellido" name="apellido" value="<?php echo sanitizar($usuario->apellido) ?>">
    </div>

    <div class="campo">
        <label for="telefono">Telefono</label>
        <input type="tel" placeholder="Tu Telefono" id="telefono" name="telefono" value="<?php echo sanitizar($usuario->telefono) ?>">
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input type="email" placeholder="Tu Email" id="email" name="email" value="<?php echo sanitizar($usuario->email) ?>">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" placeholder="Tu Password" id="password" name="password"">
    </div>

    <input class="boton" type="submit" value="Crear Cuenta">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>