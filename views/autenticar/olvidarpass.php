<div class="contenedor olvidar">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Gestiona una Nueva Contraseña</p>

        <form class="formulario" method="post">
            <div class="campo">
                <label for="Correo">Email:</label>
                <input type="email" id="Correo" name="Correo" placeholder="Tu Correo"/>
            </div>
 
            <div id="mensaje"></div>

            <div class="contenedor-boton-login">
                <input type="submit" id="btnOlvidarPass" class="boton" value="Enviar Solicitud">
            </div>

            <div class="acciones">
                <a href="/">Iniciar Sesion</a>
                <a href="/olvidarpass">¿Olvido Su Clave?</a>
            </div>
        </form>
    </div>
</div>


<?php
    $scriptJS = "
    <script src='/build/js/funciones.js'></script> 
    <script src='/build/js/olvidarpass.js'></script> 
    ";
?>