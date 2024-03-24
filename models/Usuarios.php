<?php

namespace Model;

class Usuarios extends UpTask {

    protected static $tabla = 'Usuarios';
    protected static $columnasDB = ['ID','Nombre','Correo','Clave', 'ClaveC','Token', 'ImagenPerfil', 'Estatus'];
    public $ID, $Nombre, $Correo, $Clave, $ClaveC, $Token, $Estatus;

    public function __construct($valores = [])
    {
        $this->ID=$valores['ID'] ?? '';
        $this->Nombre=$valores['Nombre'] ?? '';
        $this->Correo=$valores['Correo'] ?? '';
        $this->Clave=$valores['Clave'] ?? '';
        $this->ClaveC=$valores['ClaveC'] ?? '';
        $this->Token=$valores['Token'] ?? '';
        $this->ImagenPerfil=$valores['ImagenPerfil'] ?? '';
        $this->Estatus=$valores['Estatus'] ?? '1';
    }

    public function validarCtaNueva(){
      
        if(!$this->Nombre){
            self::$Validador['error'][] = '* Nombre y Apellido es Requerido';
        }
        if(!$this->Correo || !$this->validarEmail($this->Correo) ){
            self::$Validador['error'][] = '* Correo Valido es Requerido';
        }
        if(!$this->Clave || strlen(trim($this->Clave)) < 6){
            self::$Validador['error'][] = '* Clave es Requerida - Minimo 6 Caracteres';
        }
        if( $this->Clave !== $this->ClaveC ){
            self::$Validador['error'][] = '* Ambos Password deben ser Igual';
        }

        return self::$Validador;
    }

    public function validarActPerfil(){
      
        if(!$this->Nombre){
            self::$Validador['error'][] = '* Nombre y Apellido es Requerido';
        }
        if(!$this->Correo || !$this->validarEmail($this->Correo) ){
            self::$Validador['error'][] = '* Correo Valido es Requerido';
        }

        return self::$Validador;
    }

    public function validarRecPass(){
        if(!$this->Correo || !$this->validarEmail($this->Correo) ){
            self::$Validador['error'][] = '* Correo Valido es Requerido';
        }
        return self::$Validador;
    }

    public function validaSetNuevoPassword(){
        if(!$this->Clave || strlen(trim($this->Clave)) < 6){
            self::$Validador['error'][] = '* Clave es Requerida - Minimo 6 Caracteres';
        }
        return self::$Validador;
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

    public function hashPassword(){
        //Has el Password e insertarlo en el Objeto Login
        $this->Clave = password_hash($this->Clave, PASSWORD_BCRYPT); 
        $this->ClaveC = password_hash($this->ClaveC, PASSWORD_BCRYPT);
    }

    public function validarLogIn(){
        if(!$this->Correo){
            self::$Validador['error'][] = '* Usuario es Requerido';
         }
         if(!$this->Clave){
            self::$Validador['error'][] = '* Clave es Requerida';
         }
                 
         
        return self::$Validador;
    }

    
}