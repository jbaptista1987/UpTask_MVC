<?php

namespace Model;

class Proyectos extends UpTask {

    protected static $tabla = 'Proyectos';
    protected static $columnasDB = ['ID','Proyecto','Codigo','UsuarioID', 'Estatus'];
    public $ID, $Proyecto, $Codigo, $UsuarioID, $Estatus;

    public function __construct($valores = [])
    {
        $this->ID=$valores['ID'] ?? '';
        $this->Proyecto=$valores['Proyecto'] ?? '';
        $this->Codigo=$valores['Codigo'] ?? '';
        $this->UsuarioID=$valores['UsuarioID'] ?? '';
        $this->Estatus=$valores['Estatus'] ?? '1';
    }

    public function ValidarProyecto(){
        if(!$this->Proyecto){
            self::$Validador['error'][] = '* El Nombre del Proyecto es Requerido';
         }
     
        return self::$Validador;
    }

}