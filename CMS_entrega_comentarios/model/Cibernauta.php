<?php
namespace App\Model;

class Cibernauta {

    //Variables o atributos
    var $id;
    var $cibernauta;
    var $clave;
    var $fecha_acceso;
    var $activo;
    var $cibernautas;
    var $datos;
    var $comentarios;

    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->cibernauta = ($data) ? $data->cibernauta : null;
        $this->clave = ($data) ? $data->clave : null;
        $this->fecha_acceso = ($data) ? $data->fecha_acceso : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->cibernautas = ($data) ? $data->cibernautas : null;
        $this->datos = ($data) ? $data->datos : null;
        $this->comentarios = ($data) ? $data->comentarios : null;


    }

}



