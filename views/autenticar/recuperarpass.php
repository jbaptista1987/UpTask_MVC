<div class="contenedor recuperar">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Escribe Tu Nueva Contraseña</p>

        <form class="formulario" method="POST">
            <div class="campo">
                <label for="Clave">Nueva Contraseña:</label>
                <input type="password" id="Clave" name="Clave" placeholder="Tu Contraseña"/>
            </div>

            <div id="mensaje"></div>

            <div class="contenedor-boton-login">
                <input type="submit" id="btnNuevoPass" class="boton" value="Nueva Clave">
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
    <script src='/build/js/recuperarpass.js'></script>
    ";
?>