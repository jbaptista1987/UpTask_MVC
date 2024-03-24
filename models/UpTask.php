<?php
namespace Model;
class UpTask {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $Validador = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje) {
        static::$Validador[$tipo][] = $mensaje;
    }
    public static function getAlertas() {
        return static::$Validador;
    }

    public function setCampoObjeto($valor, $llave){
        //Asignar al Atributo que queramos al campo que necesitemos en el Objeto que queramos tambien - Magia de la Herencia 
        if($valor){
            $this->$llave = $valor;
        }
    }

    // Consulta SQL para crear un objeto en Memoria
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna){
            if($columna === 'ID' || $columna === 'Estatus' || $columna === 'captchaGoogle' || $columna === 'accion' || $columna === 'ClaveC') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->ID)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Todos los registros
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY ID";
        //return json_encode( ['query' => $query ] );
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca UN registro por cualquier condicion
    public static function findfirst($Columnas, $Condicional=''){
        $query = "SELECT " . $Columnas . " FROM " . static::$tabla . " " . $Condicional;
        //return $query;
        //ProbarVariable($query);
        $Resultado = self::consultarSQL($query);
        return array_shift( $Resultado );
    }
    // Busca UN registro por cualquier condicion
    public static function AllWhere($Columnas, $Condicional=''){
        $query = "SELECT " . $Columnas . " FROM " . static::$tabla . " " . $Condicional;
        //return $query;
        //ProbarVariable($query);
        $Resultado = self::consultarSQL($query);
        return  $Resultado;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $limite;
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('"; 
        $query .= join("','", array_values($atributos));
        $query .= "') ";
        //ProbarVariable($query);
        //Activar Si Quieres ver en consola si hay un error en el Query Armado
        //return  $query;

        // Resultado de la consulta
        $resultado = self::$db->query($query);
        return [
           'resultado' =>  $resultado,
           'ID' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'"; 
        }

        // Consulta SQLfunction
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE ID = '" . self::$db->escape_string($this->ID) . "' ";
        $query .= " LIMIT 1 "; 
        //return $query;
        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public function actNuevaClave() {
        //Nueva Clave
        $atributos = $this->sanitizarAtributos();
        
        unset($atributos["Nombre"]);
        unset($atributos["Correo"]);
        unset($atributos["ClaveC"]);
        unset($atributos["ImagenPerfil"]);
        $atributos["Token"] = "";

        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'"; 
        }
        
        // Consulta SQLfunction
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE ID='" . self::$db->escape_string($this->ID) . "' ";
        $query .= " LIMIT 1 ";
        //ProbarVariable($query);
        //return $query;
        $Resultado = self::$db->query($query);
        
        return $Resultado;
    }
    public function actNClavePerfil() {
        //Nueva Clave
        $atributos = $this->sanitizarAtributos();
        
        unset($atributos["Nombre"]);
        unset($atributos["Correo"]);
        unset($atributos["ClaveC"]);
        unset($atributos["Token"]);
        unset($atributos["ImagenPerfil"]);

        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'"; 
        }
        
        // Consulta SQLfunction
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE ID='" . self::$db->escape_string($this->ID) . "' ";
        $query .= " LIMIT 1 ";
        //ProbarVariable($query);
        //return $query;
        $Resultado = self::$db->query($query);
        
        return $Resultado;
    }

    public function actPerfil() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        unset($atributos["Clave"]);
        unset($atributos["Token"]);

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'"; 
        }

        // Consulta SQLfunction
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE ID = '" . self::$db->escape_string($this->ID) . "' ";
        $query .= " LIMIT 1 "; 
        //return $query;
        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }


    // Eliminar un Registro por su ID
    public function eliminarID($ID) {
        
        $query = "DELETE FROM "  . static::$tabla . " WHERE ID='" . self::$db->escape_string($ID) . "'";
        //return $query;
        //ProbarVariable($query);
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public function validarEmail($email) {
        // Expresión regular para validar el formato del correo electrónico
        $expresion = '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/';
    
        // Comprobar si el correo electrónico cumple con la expresión regular
        if (preg_match($expresion, $email)) {
            return true;
        } else {
            return false;
        }
     }

     public function generarToken(){
        //Crear el Token y asignarlo al Objeto
        $this->Token = str_pad(random_int(100000000, 9999999999), 10, '0', STR_PAD_LEFT);
    }

    public function actualizarToken() {
        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .= "Token='" . self::$db->escape_string($this->Token) . "'";
        $query .= " WHERE ID = '" . self::$db->escape_string($this->ID) . "'";
        $query .= " LIMIT 1"; 

        //ProbarVariable($query);
        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public static function traer($QueryPersonal) {
        $query = $QueryPersonal;
        //return json_encode( ['query' => $query ] );
        //ProbarVariable($query);
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function ejecutar($QueryPersonal) {
        $query = $QueryPersonal;
        //return json_encode( ['query' => $query ] );
        //ProbarVariable($query);
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public function ActRegistroUsuario($ID_Usuario){
        //Actualizar el registro de usuario, ponerlo en Activo y eliminar su Token ya que es de un solo uso
        $query = "UPDATE " . static::$tabla . " SET Estatus='2',Token='' WHERE ID='" . self::$db->escape_string($ID_Usuario) . "'";
        //ProbarVariable($query);
        $Resultado = self::$db->query($query);
        return $Resultado;
    }

}