<?php

namespace App;

//Inicializo sesión para poder traspasar variables entre páginas
session_start();

//Incluyo los controladores que voy a utilizar para que sean cargados por Autoload
use App\Controller\AppController;
use App\Controller\DatoController;
use App\Controller\CibernautaController;
use App\Controller\ComentarioController;

//password_hash("1234Abcd!", PASSWORD_BCRYPT, ['cost' => 12]);

/*
 * Asigno a sesión las rutas de las carpetas public y home, necesarias tanto para las rutas como para
 * poder enlazar imágenes y archivos css, js
 */
$_SESSION['public'] = '/CMS_entrega_comentarios/public/';
$_SESSION['home'] = $_SESSION['public'] . 'index.php/';

//Defino y llamo a la función que autocargará las clases cuando se instancien
spl_autoload_register('App\autoload');

function autoload($clase, $dir = null)
{
    //Directorio raíz de mi proyecto
    if (is_null($dir)) {
        $dirname = str_replace('/public', '', dirname(__FILE__));
        $dir = realpath($dirname);
    }

    //Escaneo en busca de la clase de forma recursiva
    foreach (scandir($dir) as $file) {
        //Si es un directorio (y no es de sistema) accedo y
        //busco la clase dentro de él
        if (is_dir($dir . "/" . $file) and substr($file, 0, 1) !== '.') {
            autoload($clase, $dir . "/" . $file);
        } //Si es un fichero y el nombr conicide con el de la clase
        else if (is_file($dir . "/" . $file) and $file == substr(strrchr($clase, "\\"), 1) . ".php") {
            require($dir . "/" . $file);
        }
    }

}

//Para invocar al controlador en cada ruta
function controlador($nombre = null)
{
    switch ($nombre) {
        default:
            return new AppController;
        case "datos":
            return new DatoController;
        case "cibernautas":
            return new CibernautaController;
        case "comentarios":
            return new ComentarioController;
    }

}

//Quito la ruta de la home a la que me están pidiendo
$ruta = str_replace($_SESSION['home'], '', $_SERVER['REQUEST_URI']);

//Encamino cada ruta al controlador y acción correspondientes
switch ($ruta) {

    //Front-end
    case "":
    case "/":
        controlador()->index();
        break;
    case "acerca-de":
        controlador()->acercade();
        break;
    case "datos":
        controlador()->datos();
        break;
    case (strpos($ruta, "dato/") === 0):
        controlador()->dato(str_replace("dato/", "", $ruta));
        break;
    case "comentarios":
        if (isset($_POST["comentar"])) {
            controlador("comentarios")->nuevoComentario(str_replace("comentarios/", "", $ruta));
            break;
        }else{
            controlador()->comentarios();
            break;
        }

    //Back-end
    case "admin":
    case "admin/entrar":
        controlador("cibernautas")->entrar();
        break;
    case "admin/salir":
        controlador("cibernautas")->salir();
        break;
    case "admin/cibernautas":
        controlador("cibernautas")->index();
        break;
    case "admin/cibernautas/crear":
        controlador("cibernautas")->crear();
        break;
    case (strpos($ruta, "admin/cibernautas/editar/") === 0):
        controlador("cibernautas")->editar(str_replace("admin/cibernautas/editar/", "", $ruta));
        break;
    case (strpos($ruta, "admin/cibernautas/activar/") === 0):
        controlador("cibernautas")->activar(str_replace("admin/cibernautas/activar/", "", $ruta));
        break;
    case (strpos($ruta, "admin/cibernautas/borrar/") === 0):
        controlador("cibernautas")->borrar(str_replace("admin/cibernautas/borrar/", "", $ruta));
        break;
    case "admin/datos":
        controlador("datos")->index();
        break;
    case "admin/datos/crear":
        controlador("datos")->crear();
        break;
    case (strpos($ruta, "admin/datos/editar/") === 0):
        controlador("datos")->editar(str_replace("admin/datos/editar/", "", $ruta));
        break;
    case (strpos($ruta, "admin/datos/activar/") === 0):
        controlador("datos")->activar(str_replace("admin/datos/activar/", "", $ruta));
        break;
    case (strpos($ruta, "admin/datos/home/") === 0):
        controlador("datos")->home(str_replace("admin/datos/home/", "", $ruta));
        break;
    case (strpos($ruta, "admin/datos/borrar/") === 0):
        controlador("datos")->borrar(str_replace("admin/datos/borrar/", "", $ruta));
        break;
    case "admin/comentarios":
        controlador("comentarios")->index();
        break;
    case (strpos($ruta, "admin/comentarios/activar/") === 0):
        controlador("comentarios")->activar(str_replace("admin/comentarios/activar/", "", $ruta));
        break;
    case (strpos($ruta, "admin/comentarios/borrar/") === 0):
        controlador("comentarios")->borrar(str_replace("admin/comentarios/borrar/", "", $ruta));
        break;
    case (strpos($ruta, "admin/") === 0):
        controlador("cibernautas")->entrar();
        break;


    //Resto de rutas
    default:
        controlador()->index();

}
