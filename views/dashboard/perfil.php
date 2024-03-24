<?php   include_once __DIR__ . '/../templates/dashboard_header.php';     ?>

    <!-- <div class="contenedor-sm">
        
        <form action="formulario">
            <div class="campo">
                <label for="InformePDF">Informe Cierre Proyecto:</label>
                <input type="file" id="InformePDF" name="InformePDF" accept="application/pdf"/>
            </div>
            <div class="contenedor_nueva_tarea">
                <button type="button" class="agregar_tarea" id="subirPDF">&#43; Anexar Informe</button>
                <button type="button" class="agregar_tarea" id="bajarPDF">&#43; Descargar Informe</button>
            </div>
    
            <div id="mensaje"></div>
        </div>
        </form>

    </div> -->

    <div class="contenedor-sm">
        <form action="post" class="formulario">
      
            <div class="campo">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="Nombre" name="Nombre" placeholder="Tu Nombre Completo" value="<?php echo $nombreGuardado ?>"/>
            </div>

            <div class="campo">
                <label for="Correo">Email:</label>
                <input type="email" id="Correo" name="Correo" placeholder="Tu Correo" value="<?php echo $emailGuardado ?>"/>
            </div>

            <div class="campo">
                <label for="ImagenPerfil">Imagen Perfil:</label>
                <input type="file" id="ImagenPerfil" name="ImagenPerfil" accept="image/jpeg, image/png"/>
            </div>

            <div id="mensaje"></div>
            
            <div class="contenedor-boton-login">
                <input type="submit" id="btnActUsuario" class="boton" value="Guardar Cambios">
                
            </div>

        </form>
    </div>


<?php   include_once __DIR__ . '/../templates/dashboard_footer.php';     ?>


<?php
    $scriptJS = "
    <script src='/build/js/app.js'></script>
    <script src='/build/js/funciones.js'></script> 
    <script src='/build/js/perfil.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    ";
?>