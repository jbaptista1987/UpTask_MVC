<?php   include_once __DIR__ . '/../templates/dashboard_header.php';     ?>

    <!-- <div class="contenedor-sm"> -->
        <div class="contenedor_nueva_tarea">
            <button type="button" class="agregar_tarea" id="agregar_tarea">&#43; Nueva Tarea</button>
        </div>

        <div id="filtros" class="filtros">
            <div class="filtros_inputs">
                <h2>Filtros:</h2>
                <div class="campo">
                    <label for="filtroTodas">Todas =></label>
                    <input type="radio" id="filtroTodas" name="filtro" value="" checked/>
                </div>

                <div class="campo">
                    <label for="filtroPendientes">Pendientes =></label>
                    <input type="radio" id="filtroPendientes" name="filtro" value="0"/>
                </div>

                <div class="campo">
                    <label for="filtroCompletadas">Completadas =></label>
                    <input type="radio" id="filtroCompletadas" name="filtro" value="1"/>
                </div>
            </div>
        </div>

        <div id="mensaje"></div>
        
        <ul class="listado_tareas" id="listado_tareas"></ul>
    <!-- </div> -->

<?php   include_once __DIR__ . '/../templates/dashboard_footer.php';     ?>



<?php
    $scriptJS = "
    <script src='/build/js/app.js'></script>
    <script src='/build/js/funciones.js'></script> 
    <script src='/build/js/tareas.js'></script> 
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    ";
?>