<?php   include_once __DIR__ . '/../templates/dashboard_header.php';     ?>

<div class="contenedor-sm">
        <p class="descripcion-pagina">Escribe Tu Nueva Contraseña</p>

        <form class="formulario" method="POST">
            <div class="campo">
                <label for="Clave">Contraseña:</label>
                <input type="password" id="Clave" name="Clave" placeholder="Tu Contraseña"/>
            </div>
            <div class="campo">
                <label for="ClaveC">Confirmar Contraseña:</label>
                <input type="password" id="ClaveC" name="ClaveC" placeholder="Confirma Tu Contraseña"/>
            </div>

            <div id="mensaje"></div>

            <div class="contenedor-boton-login">
                <input type="submit" id="btnNuevoPass" class="boton" value="Nueva Clave">
            </div>
        </form>
    </div>


<?php   include_once __DIR__ . '/../templates/dashboard_footer.php';     ?>


<?php
    $scriptJS = "
    <script src='/build/js/app.js'></script>
    <script src='/build/js/funciones.js'></script> 
    <script src='/build/js/nuevaclave.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    ";
?>