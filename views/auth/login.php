<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>
<?php echo "<p class='alerta error'>".$alertas['error'][0]."</p>"; ?>
<?php echo "<p class='alerta error'>".$alertas['error']['sinConfirmar']."</p>"; ?>
<form action="/" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email" 
            id="email" 
            placeholder="Tu email" 
            name="email"
            value="<?php echo s($usuario->email); ?>"
        >
            
        </div>
        <?php echo "<p class='alerta error'>".$alertas['error']['email']."</p>"; ?>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password" 
            id="password" 
            placeholder="Tu password" 
            name="password"
        >
    </div>
    <?php echo "<p class='alerta error'>".$alertas['error']['password']."</p>"; ?>
    <?php echo "<p class='alerta error'>".$alertas['error']['passwordExtension']."</p>"; ?>
    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>