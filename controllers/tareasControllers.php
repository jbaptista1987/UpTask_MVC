<?php

namespace Controllers;

use Model\Proyectos;
use Model\Tareas;
use MVC\Router;

class tareasControllers {

    public static function indexTareas() {
        header('Content-Type: application/json');

        $codigoProyecto = $_GET['Codigo'];
        $objProyecto = Proyectos::findfirst("ID, UsuarioID ", "WHERE Codigo='" . dep($codigoProyecto) . "'");

        if ( !empty($objProyecto) && $objProyecto->UsuarioID == $_SESSION['ID']){
            $objTareas = Tareas::AllWhere(" * ", "WHERE ProyectoID='" . dep($objProyecto->ID) . "'");
            echo json_encode($objTareas);
            return;
        }else{
            $respuesta = [
                'Mensaje' => 'Corregir Codigo de Proyecto',
                'Tipo' => 'error'
            ];
            echo json_encode($respuesta);
            return;
        }

        
    }

    public static function crearTareas() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            header('Content-Type: application/json');
            $objTareas = new Tareas();
            $objTareas->sincronizar($_POST);

            //Obtener Codigo del Proyecto
            $codigoProyecto = $_POST['Codigo'];
            //Buscar Los datos del Proyecto Basado en el Codigo
            $objProyecto = Proyectos::findfirst("ID, UsuarioID ", "WHERE Codigo='" . dep($codigoProyecto) . "'");

            if( !empty($objProyecto) ) {
                //Validar que el Usuario que Navega Sea Propietario de ese Proyecto
                if( $objProyecto->UsuarioID == $_SESSION['ID'] ) {
                    $objTareas->ProyectoID = $objProyecto->ID;
                }
                else{
                    $respuesta = [
                        'Mensaje' => 'Acceso No Autorizado a este Proyecto',
                        'Tipo' => 'error'
                    ];
                    echo json_encode($respuesta);
                    return;
                }
            }else{
                $respuesta = [
                    'Mensaje' => 'No se Encontro Proyecto con Dicho Codigo',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }
            
            //unset($objTareas->Fecha_Real_Fin);
        
            $ejecucion = $objTareas->crear();
            if( $ejecucion ){
                $respuesta = [
                    'Mensaje' => 'Tarea Agregada con exito',
                    'Tipo' => 'msjExito',
                    'ID' => $ejecucion['ID'],
                    'ProyectoID' => $objTareas->ProyectoID,
                    'Fecha_Prevista_Fin' => $objTareas->Fecha_Prevista_Fin,
                    'Fecha_Real_Fin' => $objTareas->Fecha_Real_Fin
                ];
                echo json_encode($respuesta);
                return;
            }else{
                $respuesta = [
                    'Mensaje' => 'Error en la Ejecucion en BD',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            } 
        }
    }

    public static function actualizarTareas() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            header('Content-Type: application/json');

            $objTareas = new Tareas();
            $objTareas->sincronizar($_POST);
            $codigoProyecto = $_POST['Codigo'];

            //Validar que el Proyecto Exista.
            $objProyecto = Proyectos::findfirst("ID, UsuarioID ", "WHERE Codigo='" . dep($codigoProyecto) . "'");
            if( empty($objProyecto) ) {
                $respuesta = [
                    'Mensaje' => 'No se Encontro Proyecto con Dicho Codigo',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            //Validar que el Usuario que Modifica Sea Propietario de ese Proyecto
            if( $objProyecto->UsuarioID !== $_SESSION['ID'] ) {
                $respuesta = [
                    'Mensaje' => 'Acceso No Autorizado a este Proyecto',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }
            
            //Proceder con la modificacion.
            $resultado = $objTareas->actualizar();
            //echo json_encode($resultado);
            //return;
            if( $resultado ){
                $respuesta = [
                    'Tipo' => 'msjExito',
                    'Mensaje'=> 'Tarea Actualizada',
                ];
                echo json_encode($respuesta);
                return;
            }
        }//Fin del Metodo POST
    }

    public static function eliminarTareas() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            header('Content-Type: application/json');

            $objTareas = new Tareas();
            $objTareas->sincronizar($_POST);
            $codigoProyecto = $_POST['Codigo'];

            //Validar que el Proyecto Exista.
            $objProyecto = Proyectos::findfirst("ID, UsuarioID ", "WHERE Codigo='" . dep($codigoProyecto) . "'");
            if( empty($objProyecto) ) {
                $respuesta = [
                    'Mensaje' => 'No se Encontro Proyecto con Dicho Codigo',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            //Validar que el Usuario que Modifica Sea Propietario de ese Proyecto
            if( $objProyecto->UsuarioID !== $_SESSION['ID'] ) {
                $respuesta = [
                    'Mensaje' => 'Acceso No Autorizado a este Proyecto',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            //Proceder con la modificacion.
            $resultado = $objTareas->eliminarID($objTareas->ID);
            if( $resultado ){
                $respuesta = [
                    'Tipo' => 'msjExito',
                    'Mensaje'=> 'Tarea Eliminada',
                    'ID' => $objTareas->ID,
                    'ProyectoID' => $objTareas->ProyectoID
                ];
                echo json_encode($respuesta);
                return;
            }
        }
    }
}
