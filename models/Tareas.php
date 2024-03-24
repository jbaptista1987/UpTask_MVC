<?php

namespace Model;

class Tareas extends UpTask{
    protected static $tabla = 'Tareas';
    protected static $columnasDB = ['ID','Tarea','ProyectoID', 'Fecha_Prevista_Fin', 'Fecha_Real_Fin','Estado'];
    public $ID, $Tarea, $ProyectoID, $Fecha_Prevista_Fin, $Fecha_Real_Fin, $Estado;

    public function __construct($valores = [])
    {
        $this->ID=$valores['ID'] ?? '';
        $this->Tarea=$valores['Tarea'] ?? '';
        $this->ProyectoID=$valores['ProyectoID'] ?? '';
        $this->Fecha_Prevista_Fin=$valores['Fecha_Prevista_Fin'] ?? '';
        $this->Fecha_Real_Fin=$valores['Fecha_Real_Fin'] ?? '';
        $this->Estado=$valores['Estado'] ?? 0;
    }

}