<div class="contenedor confirmar">
    <?php
        include_once __DIR__ . '/../templates/nombreSitio.php';
    ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Registro Confirmado</p>
        <p class="descripcion-pagina">Sera Redigiridigo en:</p>

        <div id="countdown" class="descripcion-pagina">10</div>

        <div class="contenedor-logo-redirigir">
            <img src="/build/img/loadingapp.gif" alt="Icono Loading" loading="lazy">
        </div>
    </div>
</div>

<?php
    $scriptJS = "
    <script src='/build/js/funciones.js'></script> 
    ";
?>