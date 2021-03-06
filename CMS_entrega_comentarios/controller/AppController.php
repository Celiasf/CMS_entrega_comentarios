<?php
namespace App\Controller;

use App\Model\Dato;
use App\Helper\ViewHelper;
use App\Helper\DbHelper;
use App\Model\Comentario;


class AppController
{
    var $db;
    var $view;

    function __construct()
    {
        //Conexión a la BBDD
        $dbHelper = new DbHelper();
        $this->db = $dbHelper->db;

        //Instancio el ViewHelper
        $viewHelper = new ViewHelper();
        $this->view = $viewHelper;

    }

    public function index(){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM datos WHERE activo=1 AND home=1 ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $datos = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($datos,new Dato($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "index", $datos);
    }

    public function acercade(){

        //Llamo a la vista
        $this->view->vista("app", "acerca-de");

    }

    public function datos(){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM datos WHERE activo=1 ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $datos = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($datos,new Dato($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "datos", $datos);

    }

    public function dato($slug){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM datos WHERE activo=1 AND slug='$slug' LIMIT 1");

        //Asigno resultado a una instancia del modelo
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $dato = new Dato($row);

        //Llamo a la vista
        $this->view->vista("app", "dato", $dato);

    }

    public function comentarios(){

        //Consulta a la bbdd
        $rowset = $this->db->query("SELECT * FROM comentarios WHERE activo=1 ORDER BY id DESC");

        //Asigno resultados a un array de instancias del modelo
        $comentarios = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($comentarios,new Comentario($row));
        }

        //Llamo a la vista
        $this->view->vista("app", "comentarios", $comentarios);

    }

}