<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email:</label>
        <input type="email" placeholder="Tu Email" id="email" name="email" value="<?php echo sanitizar($auth->email); ?>"/> <!-- name sirve para poder leerlo con POST - $_POST['email'] -->
    </div>

    <div class="campo">
        <label for="password">Password:</label>
        <input type="password" placeholder="Tu Password" id="password" name="password"/>
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>
