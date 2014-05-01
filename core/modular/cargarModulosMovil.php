<?php
if(!isset ($_SESSION))
    session_start();

require_once __superCore."compartido/class.cargar_variables.php";
require_once __superCore."compartido/class.xml_procesador.php";
require_once __superCore."classPresentacion/class.navegacion";
require_once __superCore.'classPresentacion/class.gestor_interfase.php';
require_once  __superCore.'classPresentacion/class.procesar_formas.php';
require_once __superCore.'classPresentacion/class.controles.php';

$_SESSION['movil'.__baseDatos]  = true;

if(isset ($_GET['msj'])){
   $controles = new controles();
   echo $controles->div_msj($_GET['msj']);
}
   
class carga_modulos {
    public  $salida;
    function __construct (){
        $variables = NULL; $interfase_indice = NULL;

        $variables = new cargar_variables();
        if (!isset($_SESSION['user'.__baseDatos])){
            $interfase_indice = new gestor_interfase("ingresoMovil.tpl","",$variables->TituloAplicacion);
            $interfase_indice->establecer_forma("ingreso.xml");
       }else{
           $interfase_indice = $this->construir_menu();
        }
        //echo "Hola";
        $this->salida = $interfase_indice->retorna_interfase();
    }
    function construir_menu($modulo=NULL){
         $variables = NULL;
        $variables = new cargar_variables();$interfase_indice= NULL;
       
        return $interfase_indice = new gestor_interfase("index.tpl",$modulo,$variables->TituloAplicacion,"","");

    }
    function confirma_cockie($rutaModulo){
        $id = NULL;
            setCookie("rutaModulo",$id,time() +3600);
        
    }
    function crea_modulo(){
        return $this->salida;
    }
}

$modulo = new carga_modulos();

echo $modulo->crea_modulo($_SESSION);
?>
