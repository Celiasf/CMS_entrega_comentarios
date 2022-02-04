<?php

namespace App\Controller;

    use App\Helper\ViewHelper;
    use App\Helper\DbHelper;
    use App\Model\Comentario;

class ComentarioController
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
        //Permisos
        echo "Entra en index";
        $this->view->permisos("comentarios");
        echo "Entra en index";
        //Recojo los comentarios de la base de datos
        $rowset = $this->db->query("SELECT * FROM comentarios ORDER BY nick ASC");

        //Asigno resultados a un array de instancias del modelo
        $comentarios = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($comentarios,new Comentario($row));
        }
        $this->view->vista("admin","comentarios/index", $comentarios);
    }
    //Para activar o desactivar
    public function activar($id){
        //Permisos
        $this->view->permisos("comentarios");
        //Obtengo el dato
        $rowset = $this->db->query("SELECT * FROM comentarios WHERE id='$id' LIMIT 1");
        //echo "rowset vale".$rowset;
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $comentario = new Comentario($row);
        if ($comentario->activo == 1){
            //Desactivo el comentario
            $consulta = $this->db->exec("UPDATE comentarios SET activo=0 WHERE id='$id'");
            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/comentarios","green","El comentario de <strong>$comentario->nick</strong> se ha desactivado correctamente.") :
                $this->view->redireccionConMensaje("admin/comentarios","red","Hubo un error al guardar en la base de datos.");
        }

        else{
            //Activo el dato
            $consulta = $this->db->exec("UPDATE comentarios SET activo=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/comentarios","green","El comentario de <strong>$comentario->nick</strong> se ha activado correctamente.") :
                $this->view->redireccionConMensaje("admin/comentarios","red","Hubo un error al guardar en la base de datos.");
        }

    }

    public function borrar($id){

        //Permisos
        $this->view->permisos("comentarios");

        //Borro el comentario
        $consulta = $this->db->exec("DELETE FROM comentarios WHERE id='$id'");

        //Mensaje y redirección
        ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
            $this->view->redireccionConMensaje("admin/comentarios","green","El comentario se ha borrado correctamente.") :
            $this->view->redireccionConMensaje("admin/comentarios","red","Hubo un error al guardar en la base de datos.");

    }

    public function nuevoComentario($id){

        //Si ha pulsado el botón de comentar
        if (isset($_POST["comentar"])) {
            //Recupero los datos del formulario
            $nick = filter_input(INPUT_POST, "nick", FILTER_SANITIZE_STRING);
            $comentario = filter_input(INPUT_POST, "comentario", FILTER_SANITIZE_STRING);

                //Imagen
                $imagen_recibida = $_FILES['imagen'];
                $imagen = $_FILES['imagen']['name'];
                $imagen_subida = ($_FILES['imagen']['name']) ? '/var/www/html' . $_SESSION['public'] . "img/" . $_FILES['imagen']['name'] : "";
                $texto_img = ""; //Para el mensaje
                //Creo un nuevo dato
                $consulta = $this->db->exec("INSERT INTO comentarios 
                (activo, imagen, nick, comentario) VALUES 
                (1, '$imagen', '$nick', '$comentario')");
                //Subo la imagen
                if ($imagen) {
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)) {
                        $texto_img = " La imagen se ha subido correctamente.";
                    } else {
                        $texto_img = " Hubo un problema al subir la imagen.";
                    }
                }
                //Mensaje y redirección
                ($consulta > 0) ?
                    $this->view->redireccionConMensaje("comentarios", "green", "El comentario se creado correctamente." . $texto_img) :
                    $this->view->redireccionConMensaje("comentarios", "red", "Hubo un error al guardar en la base de datos.");

            }


    }
}