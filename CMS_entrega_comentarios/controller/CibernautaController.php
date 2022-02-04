<?php
namespace App\Controller;

use App\Helper\ViewHelper;
use App\Helper\DbHelper;
use App\Model\Cibernauta;

class CibernautaController
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

    public function admin(){

        //Compruebo permisos
        $this->view->permisos();

        //LLamo a la vista
        $this->view->vista("admin","index");

    }

    public function entrar(){

        //Si ya está autenticado, le llevo a la página de inicio del panel
        if (isset($_SESSION['cibernauta'])){

            $this->admin();

        }
        //Si ha pulsado el botón de acceder, tramito el formulario
        else if (isset($_POST["acceder"])){

            //Recupero los datos del formulario
            $campo_cibernauta = filter_input(INPUT_POST, "cibernauta", FILTER_SANITIZE_STRING);
            $campo_clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);

            //Busco al cibernauta en la base de datos
            $rowset = $this->db->query("SELECT * FROM cibernautas WHERE cibernauta='$campo_cibernauta' AND activo=1 LIMIT 1");

            //Asigno resultado a una instancia del modelo
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $cibernauta = new Cibernauta($row);

            //Si existe el cibernauta
            if ($cibernauta){
                //Compruebo la clave
                if (password_verify($campo_clave,$cibernauta->clave)) {

                    //Asigno el usuario y los permisos la sesión
                    $_SESSION["cibernauta"] = $cibernauta->cibernauta;
                    $_SESSION["cibernautas"] = $cibernauta->cibernautas;
                    $_SESSION["datos"] = $cibernauta->datos;
                    $_SESSION["comentarios"] = $cibernauta->comentarios;

                    //Guardo la fecha de último acceso
                    $ahora = new \DateTime("now", new \DateTimeZone("Europe/Madrid"));
                    $fecha = $ahora->format("Y-m-d H:i:s");
                    $this->db->exec("UPDATE cibernautas SET fecha_acceso='$fecha' WHERE cibernauta='$campo_cibernauta'");

                    //Redirección con mensaje
                    $this->view->redireccionConMensaje("admin","green","Bienvenido al panel de administración.");
                }
                else{
                    //Redirección con mensaje
                    $this->view->redireccionConMensaje("admin","red","Contraseña incorrecta.");
                }
            }
            else{
                //Redirección con mensaje
                $this->view->redireccionConMensaje("admin","red","No existe ningún usuario con ese nombre.");
            }
        }
        //Le llevo a la página de acceso
        else{
            $this->view->vista("admin","cibernautas/entrar");
        }

    }

    public function salir(){

        //Borro al cibernauta de la sesión
        unset($_SESSION['cibernauta']);

        //Redirección con mensaje
        $this->view->redireccionConMensaje("admin","green","Te has desconectado con éxito.");

    }

    //Listado de cibernautas
    public function index(){

        //Permisos
        $this->view->permisos("cibernautas");

        //Recojo los cibernautas de la base de datos
        $rowset = $this->db->query("SELECT * FROM cibernautas ORDER BY cibernauta ASC");

        //Asigno resultados a un array de instancias del modelo
        $cibernautas = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($cibernautas,new Cibernauta($row));
        }

        $this->view->vista("admin","cibernautas/index", $cibernautas);

    }

    //Para activar o desactivar
    public function activar($id){

        //Permisos
        $this->view->permisos("cibernautas");

        //Obtengo el cibernauta
        $rowset = $this->db->query("SELECT * FROM cibernautas WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $cibernauta = new Cibernauta($row);

        if ($cibernauta->activo == 1){

            //Desactivo el cibernauta
            $consulta = $this->db->exec("UPDATE cibernautas SET activo=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/cibernautas","green","El cibernauta <strong>$cibernauta->cibernauta</strong> se ha desactivado correctamente.") :
                $this->view->redireccionConMensaje("admin/cibernautas","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Activo el cibernauta
            $consulta = $this->db->exec("UPDATE cibernautas SET activo=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/cibernautas","green","El cibernauta <strong>$cibernauta->cibernauta</strong> se ha activado correctamente.") :
                $this->view->redireccionConMensaje("admin/cibernautas","red","Hubo un error al guardar en la base de datos.");
        }

    }

    public function borrar($id){

        //Permisos
        $this->view->permisos("cibernautas");

        //Borro el cibernauta
        $consulta = $this->db->exec("DELETE FROM cibernautas WHERE id='$id'");

        //Mensaje y redirección
        ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
            $this->view->redireccionConMensaje("admin/cibernautas","green","El cibernauta se ha borrado correctamente.") :
            $this->view->redireccionConMensaje("admin/cibernautas","red","Hubo un error al guardar en la base de datos.");

    }

    public function crear(){

        //Permisos
        $this->view->permisos("cibernautas");

        //Creo un nuevo usuario vacío
        $cibernauta = new Cibernauta();

        //Llamo a la ventana de edición
        $this->view->vista("admin","cibernautas/editar", $cibernauta);

    }

    public function editar($id){

        //Permisos
        $this->view->permisos("cibernautas");

        //Si ha pulsado el botón de guardar
        if (isset($_POST["guardar"])){

            //Recupero los datos del formulario
            $cibernauta = filter_input(INPUT_POST, "cibernauta", FILTER_SANITIZE_STRING);
            $clave = filter_input(INPUT_POST, "clave", FILTER_SANITIZE_STRING);
            $cibernautas = (filter_input(INPUT_POST, 'cibernautas', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;
            $datos = (filter_input(INPUT_POST, 'datos', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;
            $cambiar_clave = (filter_input(INPUT_POST, 'cambiar_clave', FILTER_SANITIZE_STRING) == 'on') ? 1 : 0;

            //Encripto la clave
            $clave_encriptada = ($clave) ? password_hash($clave,  PASSWORD_BCRYPT, ['cost'=>12]) : "";

            if ($id == "nuevo"){

                //Creo un nuevo cibernauta
                $this->db->exec("INSERT INTO cibernautas (cibernauta, clave, datos, cibernautas) VALUES ('$cibernauta','$clave_encriptada',$datos,$cibernautas)");

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/cibernautas","green","El cibernauta <strong>$cibernauta</strong> se creado correctamente.");
            }
            else{

                //Actualizo el cibernautas
                ($cambiar_clave) ?
                    $this->db->exec("UPDATE cibernautas SET cibernauta='$cibernauta',clave='$clave_encriptada',datos=$datos,cibernautas=$cibernautas WHERE id='$id'") :
                    $this->db->exec("UPDATE cibernautas SET cibernauta='$cibernauta',datos=$datos,cibernautas=$cibernautas WHERE id='$id'");

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/cibernautas","green","El cibernauta <strong>$cibernauta</strong> se actualizado correctamente.");
            }
        }

        //Si no, obtengo cibernauta y muestro la ventana de edición
        else{

            //Obtengo el cibernauta
            $rowset = $this->db->query("SELECT * FROM cibernautas WHERE id='$id' LIMIT 1");
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $cibernauta = new Cibernauta($row);

            //Llamo a la ventana de edición
            $this->view->vista("admin","cibernautas/editar", $cibernauta);
        }

    }


}