<?php   include_once __DIR__ . '/../templates/dashboard_header.php';     ?>

    <div class="contenedor-sm">
        <form class="formulario" method="POST" action="/crear_proyectos">
 
            <?php include_once __DIR__ . '/../templates/formulario_proyectos.php'; ?>

            <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
            <input type="submit" value="Crear Proyecto">
        </form>
    </div>

<?php   include_once __DIR__ . '/../templates/dashboard_footer.php';     ?>

<?php
    $scriptJS = "
    <script src='/build/js/app.js'></script>
    <script src='/build/js/funciones.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    ";
?>