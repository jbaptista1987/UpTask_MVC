<?php

namespace Controllers;

use Model\Proyectos;
use Model\Usuarios;
use MVC\Router;

class dashboardControllers {

    public static function principal(Router $router) {
        Loggeado();
        $queryString = "SELECT * FROM Proyectos WHERE UsuarioID='" . $_SESSION['ID'] . "'";
        $ObjProyecto = Proyectos::traer($queryString);

        $router->render('dashboard/principal', [
            //'Validador' => $Validador,
            'ObjProyecto' => $ObjProyecto,
            'Titulo' => 'Proyectos',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function crear_proyectos(Router $router) {
        Loggeado();
        $Validador = [];

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $ObjProyecto = new Proyectos($_POST);
            $Validador = $ObjProyecto->ValidarProyecto();

            if( empty($Validador) ) {
                //Crear URL o Codigo
                $ObjProyecto->Codigo = md5( uniqid() );
                $ObjProyecto->UsuarioID = $_SESSION['ID'];

                $Ejecutar = $ObjProyecto->crear();
                if ($Ejecutar['resultado'] ){
                    header('Location: /proyecto?Codigo=' . $ObjProyecto->Codigo);
                }
            }
        }

        $router->render('dashboard/crear_proyectos', [
            'Validador' => $Validador,
            'Titulo' => 'Crear Proyectos',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function proyecto(Router $router) {
        Loggeado();

        $ObjProyecto = Proyectos::findfirst(" * ", " WHERE Codigo='" . $_GET['Codigo'] . "'");

        if( $ObjProyecto->UsuarioID !== $_SESSION['ID'] ){
            header('location: /panelprincipal');
        }

    
        $router->render('dashboard/proyecto', [
            //'Validador' => $Validador,
            'boton_back' => true,
            'Titulo' => $ObjProyecto->Proyecto,
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function perfil(Router $router) {
        Loggeado();

        $nombreGuardado = $_SESSION['Nombre'];
        $emailGuardado = $_SESSION['Correo'];

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            header('Content-Type: application/json');

            //Llenar el objeto de Login y Validar sus campos esten llenos
            $ObjLogin = new Usuarios();
            $ObjLogin->sincronizar($_POST);
            $ObjLogin->ID = $_SESSION['ID'];
            $imagen = $_FILES['ImagenPerfil'];

            //Validar que los campos esten llenos
            $Validador = $ObjLogin->validarActPerfil();
            if( !empty($Validador || empty($imagen))  ) {
                $respuesta = [
                    'Mensaje' => '* Debe llenar Todo el Formulario y Cargar Imagen',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            //Validar que el correo no este ya registrado
            $valCorreo = Usuarios::findfirst("ID", "WHERE Correo='" . $ObjLogin->Correo . "' AND  ID!='" . $ObjLogin->ID . "'" );
            if ( !empty($valCorreo) ) {
                $respuesta = [
                    'Mensaje' => '* Correo en Uso',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }
            //Obtener Extension - Generar Nombre Unico a la Imagen - Introducirla en el Objeto
            if($imagen['type'] === 'image/png') {
                $extension = '.png';
            }else{
                $extension = '.jpg';
            }
            $nombreImg = md5( uniqid( rand(), true ) ) . $extension;  
            $ObjLogin->ImagenPerfil = $nombreImg;
            ActImgOptimizada($nombreImg, $imagen['tmp_name'], $extension, 256, 256, $_SESSION['ImagenPerfil']);

            $resultado =  $ObjLogin->actPerfil();
            if ($resultado) {
                $_SESSION['ImagenPerfil'] = $ObjLogin->ImagenPerfil;
                $respuesta = [
                    'Mensaje' => 'Perfil Actualizado',
                    'Tipo' => 'msjExito'
                ];
                echo json_encode($respuesta);
                return;
            }

            echo json_encode($_FILES);
            return;
            
        }

        $router->render('dashboard/perfil', [
            //'ObjLogin' => $ObjLogin,
            'nombreGuardado' => $nombreGuardado,
            'emailGuardado' => $emailGuardado,
            'Titulo' => 'Perfil',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }
}