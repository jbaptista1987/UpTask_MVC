<div class="dashboard">

<!-- INICIO SIDE BAR (BARRA LATERAL) -->
<aside class="sidebar">
        
        <div class="menu">
            <h2>upTask</h2>
            <img id="menu_mobile" src="build/img/menu.svg" alt="Imagen Menu Mobile">
        </div>

        <nav class="sidebar_nav">
            <a class="<?php echo ($Titulo === 'Proyectos') ? 'activo' : ''; ?>" href="/panelprincipal">
            <?php
                if( $Titulo === 'Proyectos' ){
                    echo '<i class="fa-solid fa-square-check"></i>';
                }
            ?>
            Proyectos
            </a>

            <a class="<?php echo ($Titulo === 'Crear Proyectos') ? 'activo' : ''; ?>" href="/crear_proyectos">
            <?php
                if( $Titulo === 'Crear Proyectos' ){
                    echo '<i class="fa-solid fa-square-check"></i>';
                }
            ?>
            Crear Proyectos
            </a>

            <a class="<?php echo ($Titulo === 'Perfil') ? 'activo' : ''; ?>" href="/perfil">
            <?php
                if( $Titulo === 'Perfil' ){
                    echo '<i class="fa-solid fa-square-check"></i>';
                }
            ?>
            Perfil
            </a>

            <a class="<?php echo ($Titulo === 'Nueva Clave') ? 'activo' : ''; ?>" href="/clavenueva">
            <?php
                if( $Titulo === 'Nueva Clave' ){
                    echo '<i class="fa-solid fa-square-check"></i>';
                }
            ?>
            Clave
            </a>
        </nav>
    </aside>
<!-- FINAL DEL SIDE BAR (BARRA LATERAL) -->

    <div class="principal">
        
        <div class="barra">
            <p class="primera">Hola: <span><?php echo $_SESSION['Nombre']; ?></span></p>
            <img class="ImagenPerfil segunda" src="/imgPerfil/<?php echo $_SESSION['ImagenPerfil']; ?>" alt="Img de <?php echo $_SESSION['Nombre']; ?>">

            <a href="/logout" class="cerrarSesion elemento-tercera-columna combinada">
                <i class="fa-solid fa-power-off"></i>
                Cerrar Sesion
            </a>
        </div>

        <div class="contenido">
            <?php if( isset($boton_back) ) { ?>
                <div class="titulo_proyecto">
                    <a href="/panelprincipal">
                        <svg class="icono_titulo" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#8db600" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                          <path d="M5 12l14 0" />
                          <path d="M5 12l6 6" />
                          <path d="M5 12l6 -6" />
                        </svg>
                    </a>
                    <h2 class="nombre_pagina"><?php echo $Titulo; ?></h2>
                </div>
            <?php }else{?>
                <h2 class="nombre_pagina"><?php echo $Titulo; ?></h2>
            <?php }?>
            