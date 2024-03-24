<?php

namespace Controllers;

use MVC\Router;
use Model\Usuarios;
//use MegaCreativo\API\CedulaVE;

class loginControllers {

    public static function login(Router $router){
        
        $ObjLogin = new Usuarios();   
        //$resultado = CedulaVE::info('V', '17567906', false); 
        //ProbarVariable($resultado);  
        
        
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            header('Content-Type: application/json');

            //Llenar el Objeto de Login con los datos de Usuario, Clave y Captcha Validado
            $ObjLogin->sincronizar($_POST);
            
            //Validar que los datos esten llenos
            $Validador = $ObjLogin->validarLogIn();
            
            if( !empty( $Validador ) ) {
                $respuesta = [
                    'Mensaje' => 'Usuario y Clave Son Obligatorio',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            } // Fin de la Validacion del llenado de formulario

                //Validar en BD el Usuario y la Clave
            $LoginApp = Usuarios::findfirst("ID, Clave, Nombre, ImagenPerfil ", "WHERE Correo='" . dep($ObjLogin->Correo) . "' AND Estatus='2'");
                if( empty($LoginApp) ) {
                    $respuesta = [
                        'Mensaje' => 'Usuario o Clave Incorrectos',
                        'Tipo' => 'error'
                    ];
                    echo json_encode($respuesta);
                    return;
                }
                    
                if ( password_verify(dep($ObjLogin->Clave), $LoginApp->Clave) ) {
                    $_SESSION['ID'] = $LoginApp->ID;
                    $_SESSION['Loggeado'] = true;
                    //Datos del Cliente
                    $_SESSION['Nombre'] = $LoginApp->Nombre;
                    $_SESSION['Correo'] = $ObjLogin->Correo; 
                    $_SESSION['ImagenPerfil'] = $LoginApp->ImagenPerfil;
                    
                    $respuesta = [
                        'Mensaje' => 'Exito',
                        'Tipo' => 'msjExito'
                    ];
                    echo json_encode($respuesta);
                    return;
                }
                else{
                    $respuesta = [
                        'Mensaje' => 'Usuario o Clave Incorrectos',
                        'Tipo' => 'error'
                    ];
                    echo json_encode($respuesta);
                    return;
                }
        } //Fin del Metodo POST

        $Validador = Usuarios::getAlertas();

        $router->render('autenticar/login', [
            'ObjLogin' => $ObjLogin,
            'Titulo' => 'Inicio de Sesion',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function logout(){
        $_SESSION = [];
        header('Location: /');
    }

    public static function crearcta(Router $router){
        //Objeto y Validador para Usuario
        $ObjLogin = new Usuarios();
        
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            header('Content-Type: application/json');
            //Llenar el objeto de Login y Validar sus campos esten llenos
            $ObjLogin->sincronizar($_POST);
            $imagen = $_FILES['ImagenPerfil'];

            //Validar que los campos esten llenos
            $Validador = $ObjLogin->validarCtaNueva();
            if( !empty($Validador)  ) {
                $respuesta = [
                    'Mensaje' => '* Debe llenar Todo el Formulario',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            //Validar que el correo no este ya registrado
            $valCorreo = Usuarios::findfirst("ID", "WHERE Correo='" . $ObjLogin->Correo . "'");
            if ( !empty($valCorreo) ) {
                $respuesta = [
                    'Mensaje' => '* Correo en Uso',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            } 
            
            //Generar un password Encriptado
            $ObjLogin->hashPassword();
            
            //Generar el Token para Aprobar el Usuario
            $ObjLogin->generarToken();

            //Obtener Extension - Generar Nombre Unico a la Imagen - Introducirla en el Objeto
            if($imagen['type'] === 'image/png') {
                $extension = '.png';
            }else{
                $extension = '.jpg';
            }
            $nombreImg = md5( uniqid( rand(), true ) ) . $extension;  
            $ObjLogin->ImagenPerfil = $nombreImg;
            GuardarImgOptimizada($nombreImg, $imagen['tmp_name'], $extension, 256, 256);


            $resultado =  $ObjLogin->crear();
            if ($resultado['resultado']) {
                enviarTokenEmailResend($ObjLogin->Nombre, $ObjLogin->Token, $ObjLogin->Correo);
                $respuesta = [
                    'Mensaje' => 'Reg Exitoso',
                    'Tipo' => 'msjExito'
                ];
                echo json_encode($respuesta);
                return;
            }
        }
        $router->render('autenticar/crearcta', [
            'ObjLogin' => $ObjLogin,
            'Titulo' => 'Crea Tu Cuenta',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }
    
    public static function confirmarcta(Router $router){
        $ObjLogin = new Usuarios($_GET);
        $valTokenEmail = Usuarios::findfirst("ID", "WHERE Correo='" . dep($ObjLogin->Correo) . "' AND Token='" . dep($ObjLogin->Token) . "'");
        if ( !empty($valTokenEmail) ) {
            //Modifico el Registro

            $valTokenEmail->ActRegistroUsuario( $valTokenEmail->ID );
            if ( !$valTokenEmail ) {
                header('Location: /');
            }
            
        }else{
            header('Location: /');
        }
        $router->render('autenticar/confirmarcta', [
            'Titulo' => 'Usuario Activado',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function olvidarpass(Router $router){
        
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            header('Content-Type: application/json');
            
            $ObjCliente = new Usuarios($_POST);
            $Validador = $ObjCliente->validarRecPass();
            
            if( !empty($Validador) ) {
                $respuesta = [
                    'Mensaje' => '* Correo es Obligatorio',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;                
            }

            //Buscar los datos necesarios para envio de Token al correo
            $valUsuarioCorreo = Usuarios::findfirst("ID, Nombre", "WHERE Correo='" . dep($ObjCliente->Correo) . "'");
                
            if ( empty($valUsuarioCorreo) ){
                $respuesta = [
                    'Mensaje' => '* Usuario No Registrado o No Confirmado',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return; 
            }

            $valUsuarioCorreo->Correo = dep($ObjCliente->Correo);
                
            //Ejecuto el proceso de recuperacion
            $valUsuarioCorreo->generarToken();
            $resultado = $valUsuarioCorreo->actualizarToken();
            if ($resultado){
                tokenRecPassResend($valUsuarioCorreo->Nombre, $valUsuarioCorreo->Token, $valUsuarioCorreo->Correo);
                $respuesta = [
                    'Mensaje' => '* Token enviado al correo. Confirmar el enlace',
                    'Tipo' => 'msjExito'
                ];
                echo json_encode($respuesta);
                return;
            }
        }//Fin del Metodo POST
        
        $router->render('autenticar/olvidarpass', [
            //'ObjLogin' => $ObjLogin,
            'Titulo' => 'Olvide Mi Contraseña',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function recuperarpass(Router $router){
        $Token = $_GET['Token'];
        $Correo = $_GET['Correo'];
        
        //Validar que tanto usuario como Token existen en la BD
        $valTokenUser = Usuarios::findfirst('ID', "WHERE Correo='" . dep($Correo) . "' AND Token='" . dep($Token) . "' AND Estatus='2'");
        
        if( empty($valTokenUser) ){
            header('Location: /');
            exit();
        }


        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            $ObjLogin = new Usuarios();
            $ObjLogin->ID = $valTokenUser->ID;
            $ObjLogin->Clave = dep($_POST['Clave']);
            $Validador = [];
            $Validador = $ObjLogin->validaSetNuevoPassword();
            
            if ( !empty($Validador) ){
                $respuesta = [
                    'Mensaje' => '* Clave es Requerida - Minimo 6 Caracteres',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }   

            $ObjLogin->hashPassword();
            $nuevaClave = $ObjLogin->actNuevaClave(); 
            if ( $nuevaClave ) {
                $respuesta = [
                    'Mensaje' => '* Clave Actualizada con Exito. Inicie Sesion',
                    'Tipo' => 'msjExito'
                ];
                echo json_encode($respuesta);
                return;
            }
        }

        $router->render('autenticar/recuperarpass', [
            'Titulo' => 'Nueva Contraseña',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function claveNueva(Router $router){


        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $ObjLogin = new Usuarios();
            $ObjLogin->sincronizar($_POST);
            $ObjLogin->ID = $_SESSION['ID'];

            $Validador = $ObjLogin->validaSetNuevoPassword();
            if ( !empty($Validador) ){
                $respuesta = [
                    'Mensaje' => '* Clave es Requerida - Minimo 6 Caracteres',
                    'Tipo' => 'error'
                ];
                echo json_encode($respuesta);
                return;
            }

            $ObjLogin->hashPassword();
            $nuevaClave = $ObjLogin->actNClavePerfil(); 
            if ( $nuevaClave ) {
                $respuesta = [
                    'Mensaje' => '* Clave Actualizada con Exito.',
                    'Tipo' => 'msjExito'
                ];
                echo json_encode($respuesta);
                return;
            }

        }

        $router->render('dashboard/clavenueva', [
            'Titulo' => 'Nueva Clave',
            'Mensaje' => 'Lo Lograste Baby...'
        ]);
    }

    public static function crudusuarios(Router $router) {
        /*
        Loggeado();
        esAdmin();
        
        $query = "SELECT ID, Correo, Nombre, Estatus ";
        $query .= "FROM Usuarios ";

        $ObjLogin = Usuarios::traer($query);

        $ObjLogin2 = new Usuarios();
        
       
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            header('Content-Type: application/json');

            if($_POST['accion'] == 'modificar'){
                $IDs = $_POST['IDs'];
                $nuevoEstatus = $_POST['estatus'];
                $query = "UPDATE Usuarios SET Estatus='" . $nuevoEstatus . "' WHERE ID IN" .  $IDs;

                $objAdminUsuario = Usuarios::ejecutar($query);
                
                echo json_encode($objAdminUsuario);
                return;
            }
            
            if($_POST['accion'] == 'crear') {
                header('Content-Type: application/json');
                $ObjLogin2 = new Usuarios($_POST);
                $Validador = array();
                $Validador = $ObjLogin2->validarCtaNueva();
                
                if( empty($Validador) ){
                    $ObjLogin2->hashPassword();

                    //Validar que el usuario no este en uso
                    $valUsuarioCorreo = Usuarios::findfirst("ID", "WHERE Usuario='" . $ObjLogin2->Usuario . "'");

                    if ( !empty($valUsuarioCorreo) ) {
                        echo json_encode( ['ErrorU' => 'Usuario en Uso' ] );
                        return;                     
                    }
                    else{
                        $valUsuarioCorreo = Usuarios::findfirst("ID", "WHERE Correo='" . $ObjLogin2->Correo . "'");
                        if ( !empty($valUsuarioCorreo) ) {
                            echo json_encode( ['ErrorC' => 'Correo en Uso' ] );
                            return;
                        }
                        else{
                            //Registrar al Usuario
                            $resultado =  $ObjLogin2->crear();
                            if ($resultado['resultado']) {
                                echo json_encode($resultado);
                                return;
                            }
                            
                        }
                            
                    }
                }
            }
        }

        $router->render('autenticar/crudusuarios', [
            'ObjLogin' => $ObjLogin,
            'ObjLogin2' => $ObjLogin2,
            //'Validador' => $Validador,
            'nombre'=> $_SESSION['Nombre'] . " " . $_SESSION['Apellido'],
            'clienteID' => $_SESSION['ID'],
            'Mensaje' => 'Lo Lograste Baby...'
        ]);*/
    }
}