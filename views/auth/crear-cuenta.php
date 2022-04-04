<h1 class="nombre-pagina">Crear cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<form action="/crear-cuenta" class="formulario" method="POST">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            placeholder="Tu nombre" 
            value="<?php echo s($usuario->nombre); ?>"
        >
    </div>
    <?php echo "<p class='alerta error'>".$alertas['error']['nombre']."</p>"; ?>
    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text" 
            id="apellido" 
            name="apellido" 
            placeholder="Tu apellido" 
            value="<?php echo s($usuario->apellido); ?>"
        >
    </div>
    <?php echo "<p class='alerta error'>".$alertas['error']['apellido']."</p>"; ?>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel" 
            id="telefono" 
            name="telefono" 
            placeholder="Tu Teléfono" 
            value="<?php echo s($usuario->telefono); ?>"
        >
    </div>
    <?php echo "<p class='alerta error'>".$alertas['error']['telefono']."</p>"; ?>
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            placeholder="Tu Email" 
            value="<?php echo s($usuario->email); ?>"
        >
    </div>
    <?php echo "<p class='alerta error'>".$alertas['error']['email']."</p>"; ?>
    <?php echo "<p class='alerta error'>".$alertas['error']['yaExiste']."</p>"; ?>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Tu Password" 
        >
    </div>
    <?php echo "<p class='alerta error'>".$alertas['error']['password']."</p>"; ?>
    <?php echo "<p class='alerta error'>".$alertas['error']['passwordExtension']."</p>"; ?>
    <input type="submit" value="Crear cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>