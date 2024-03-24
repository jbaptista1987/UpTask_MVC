
<div class="contenedor login">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesion</p>

        <form action="/" class="formulario" method="post">
            <div class="campo">
                <label for="Correo">Email:</label>
                <input type="email" id="Correo" name="Correo" placeholder="Tu Correo"/>
            </div>
            <div class="campo">
                <label for="Clave">Contraseña:</label>
                <input type="password" id="Clave" name="Clave" placeholder="Tu Contraseña"/>
            </div>

            <div id="mensaje"></div>
            
            <div class="contenedor-boton-login">
                <input type="submit" id="btnLogin" class="boton" value="Log In">
            </div>

            <div class="acciones">
                <a href="/crearcta">Registrese Aqui</a>
                <a href="/olvidarpass">¿Olvido Su Clave?</a>
            </div>
        </form>
    </div>
</div>


<?php
    $scriptJS = "
    <script src='/build/js/funciones.js'></script> 
    <script src='/build/js/login.js'></script>
    ";
?>