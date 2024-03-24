<?php   include_once __DIR__ . '/../templates/dashboard_header.php';     ?>

    <?php if( count($ObjProyecto) === 0 ){ ?>
        <p class="no_proyectos">No Hay Proyectos Aún Registrados con tu Usuario</p>
    <?php } else {   ?>
        <ul class="listado_proyectos">
            <?php foreach($ObjProyecto as $proyecto) { ?>
                <li class="proyecto">
                    <a href="/proyecto?Codigo=<?php echo $proyecto->Codigo; ?>">
                        <div class="proyecto_contenido">
                            <?php echo $proyecto->Proyecto ?>
                        </div>                        
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

<?php   include_once __DIR__ . '/../templates/dashboard_footer.php';     ?>

<?php
    $scriptJS = "
    <script src='/build/js/app.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    ";
?>