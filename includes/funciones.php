<?php

define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL',  __DIR__ . 'funciones.php');

function incluirTemplates( $nombre, $inicio = false ){
    include TEMPLATES_URL . '/' . $nombre . '.php';
}

function Loggeado() : void {
    if( !isset( $_SESSION['Loggeado'] ) ){
        $_SESSION = [];
        header('Location: /');
    }
    
    
}

function esAdmin(){
    if(  $_SESSION['Tipo_Usuario'] !== '3' ) {
        $_SESSION = [];
        header('Location: /');
    }
}

function ProbarVariable($tuVariable, $Mensaje_Prueba=''){
    echo "<pre>";
    var_dump($tuVariable);
    echo "<hr>";
    echo $Mensaje_Prueba;
    echo "</pre>";
    exit();
    /*
    var_dump($_FILES); -> Subida de Archivos.
    echo $_SERVER['PHP_SELF']; -> Ruta Actual*/
}


function GuardarImgOptimizada($Nombre_Unico,$Nombre_Tmp_Origen, $extension, $Nuevo_ancho, $Nuevo_Largo){
    //Crear el Directorio en caso de que no exista
    $directorioImg = 'imgPerfil/';
    //Si la Carpeta no existe, se debe crear
    if (!is_dir($directorioImg)){
        mkdir($directorioImg);
    }
    move_uploaded_file($Nombre_Tmp_Origen, $directorioImg . $Nombre_Unico);
    
    // Cargar la Imagen Fuente
    if($extension ==='.png'){
        $image_editada = imagecreatefrompng($directorioImg . $Nombre_Unico); 
    }else{
        $image_editada = imagecreatefromjpeg($directorioImg . $Nombre_Unico);
    }        
 
    // Obtener los nuevos actuales de ancho y alto de la imagen
    $width = imagesx($image_editada);
    $height = imagesy($image_editada);

    // Establecer los nuevos valores de ancho y alto de la imagen
    $new_width = $Nuevo_ancho;
    $new_height = $Nuevo_Largo;

    // CRear una imagen en blanco con las demensiones deseadas
    $destination = imagecreatetruecolor($new_width, $new_height);

    // Ponerle a la imagen recien creada (Que esta en Blanco) los valores nuevos
    imagecopyresampled($destination, $image_editada, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Guardar los cambios
    if($extension==='png'){
        imagepng($destination, $directorioImg . $Nombre_Unico);
    }else{
        imagejpeg($destination, $directorioImg . $Nombre_Unico);
    }
}

function ActImgOptimizada($Nombre_Unico,$Nombre_Tmp_Origen, $extension, $Nuevo_ancho, $Nuevo_Largo, $imgDelete){
    //Crear el Directorio en caso de que no exista
    $directorioImg = 'imgPerfil/';
    
    //Borrar el archivo anterior
    unlink($directorioImg . $imgDelete);
    //Guardar la Nueva Imagen
    move_uploaded_file($Nombre_Tmp_Origen, $directorioImg . $Nombre_Unico);
    
    // Cargar la Imagen Fuente
    if($extension ==='.png'){
        $image_editada = imagecreatefrompng($directorioImg . $Nombre_Unico); 
    }else{
        $image_editada = imagecreatefromjpeg($directorioImg . $Nombre_Unico);
    }        
 
    // Obtener los nuevos actuales de ancho y alto de la imagen
    $width = imagesx($image_editada);
    $height = imagesy($image_editada);

    // Establecer los nuevos valores de ancho y alto de la imagen
    $new_width = $Nuevo_ancho;
    $new_height = $Nuevo_Largo;

    // CRear una imagen en blanco con las demensiones deseadas
    $destination = imagecreatetruecolor($new_width, $new_height);

    // Ponerle a la imagen recien creada (Que esta en Blanco) los valores nuevos
    imagecopyresampled($destination, $image_editada, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Guardar los cambios
    if($extension==='png'){
        imagepng($destination, $directorioImg . $Nombre_Unico);
    }else{
        imagejpeg($destination, $directorioImg . $Nombre_Unico);
    }
}

function dep($cod_html){
    $s = htmlspecialchars($cod_html);
    return $s;
}

//Emvio con Call Me Bot - No Sirve para Clientes solo para numeros que le envien un msj previo al bot y reciban la clave o llave
function send_whatsapp($message="Test"){
    $phone = $_ENV['CallMeBot_phone'];
    $apikey = $_ENV['CallMeBot_key'];

    $url='https://api.callmebot.com/whatsapp.php?source=php&phone='.$phone.'&text='.urlencode($message).'&apikey='.$apikey;

    if($ch = curl_init($url))
    {
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $html = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // echo "Output:".$html;  // you can print the output for troubleshooting
        curl_close($ch);
        return (int) $status;
    }
    else
    {
        return false;
    }
}



//ENVIO DE SMS VIA WHATSAPP CON ULTRAMSG - CUESTA 39$ / MES (SOLO DA 3 DIAS DE PRUEBA GRATIS - A NIVEL EMPRESARIAL ES RECOMENDABLE)
function EnviarSMSWhatsapp($token){

    $params=array(
    'token' => $_ENV['ULTRAMSG_key'],
    'to' => '+584142940454',
    'body' => 'Saludos Claudia. Jesus esta Probando el envio de SMS de Whatsapp a traves del Sistema APP Salon. Su Nueva Clave de APP Salon es.....' . $token
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.ultramsg.com/instance77260/messages/chat",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => http_build_query($params),
      CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      return $response;
    }
}

function EnviarImgWhatsapp() {

$params=array(
'token' => $_ENV['ULTRAMSG_key'],
'to' => '+584242085877',
'image' => 'https://appsalon.com.au/wp-content/uploads/2023/10/Logo-Facebook-Sharing-Image-JPEG-1200x630-1.jpg',
'caption' => 'image AppSalon Reset Password'
);
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ultramsg.com/instance77260/messages/image",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query($params),
  CURLOPT_HTTPHEADER => array(
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
}else{
    return $response;
}
}

function enviarTokenEmailResend( $NombreApellidoUser, $Token, $Correo) {
    $resend = Resend::client( $_ENV['Resend_key'] );
    try {
        $contenido = "<html>";
        $contenido .= "<p>Buen dia <strong>" . $NombreApellidoUser . "</strong> - Tienes un nuevo Mensaje desde Registro de Usuario...</p>";
        $contenido .= "<p>Confirma tu Registro en el siguiente enlace: </p>";
        $contenido .= "<p> <a href='" . $_ENV['PROJECT_URL'] . "/confirmarcta?Token=" . $Token . "&Correo=" . $Correo ."'>AQUI...</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje<p>";
        $contenido .= '</html>';
        $result = $resend->emails->send([
            'from' => 'UpTask <onboarding@resend.dev>',
            //'to' => [$Correo],
            'to' => ['ing.jb87@gmail.com'],
            'subject' => 'Confirma tu Cuenta - Sistema UpTask_MVC',
            'html' => $contenido,
        ]);
    } catch (\Exception $e) {
        exit('Error: ' . $e->getMessage());
    }
    // Show the response of the sent email to be saved in a log...
    //echo $result->toJson();
}

function tokenRecPassResend( $NombreApellidoUser, $Token, $Correo) {
    $resend = Resend::client( $_ENV['Resend_key'] );
    try {
        $contenido  = "<html>";
        $contenido .= "<p>Buen dia <strong>" . $NombreApellidoUser . "</strong> - Tienes un nuevo Mensaje desde Recuperar Clave...</p>";
        $contenido .= "<p>Confirma tu Cambio de Clave en el siguiente enlace: </p>";
        $contenido .= "<p> <a href='" . $_ENV['PROJECT_URL'] . "/recuperarpass?Token=" . $Token . "&Correo=" . $Correo ."'>AQUI...</a></p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje o Notificar al Administrador de Sistemas<p>";
        $contenido .= "</html>";
        //ProbarVariable($contenido);
        
        $result = $resend->emails->send([
            'from' => 'UpTask <onboarding@resend.dev>',
            //'to' => [$Correo],
            'to' => ['ing.jb87@gmail.com'],
            'subject' => 'Recupera tu Password - Sistema UpTask_MVC',
            'html' => $contenido,
        ]);
    } catch (\Exception $e) {
        exit('Error: ' . $e->getMessage());
    }
    // Show the response of the sent email to be saved in a log...
    //echo $result->toJson();
}

function esUltimo($actual, $proximo) : bool {
    if( $actual !== $proximo ) {
        return true;
    }
    return false;
}