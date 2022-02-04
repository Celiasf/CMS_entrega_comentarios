<?php

namespace App\Model;

class Comentario
{

    //Variables o atributos
    var $id;
    var $nick;
    var $comentario;
    var $activo;
    var $imagen;

    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->nick = ($data) ? $data->nick : null;
        $this->comentario = ($data) ? $data->comentario : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->imagen = ($data) ? $data->imagen : null;

    }

}