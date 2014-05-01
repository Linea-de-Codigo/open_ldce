<?php

if (!isset($_SESSION)) {   session_start(); }

require_once  __superCore."compartido/class.cargar_variables.php";
require_once __superCore."compartido/class.xml_procesador.php";
require_once __superCore."classPresentacion/class.controles.php";
require_once __superCore."classPresentacion/class.controlesJson.php";

    $controles = new controles();
    $variables = new cargar_variables();
include __superCore.'classPresentacion/class.gestor_interfase.php';
include __superCore.'classPresentacion/class.procesar_formas.php';
include __superCore."classPresentacion/class.navegacion";
require_once  __superCore."classNegocio/tabla.php";
$navegador = new navegacion($_GET['dir']);
//echo $_GET['dir'];

$salida = NULL;
if (!isset ($_SESSION['empleado'.__baseDatos])){
              $salida.="<h1>La session ha Caducado por Favor <a href = \"core/salir.php\">Cerrar el Sistema</a></h1>";       
}      


$llave=NULL;$valorLlave=NULL;$FEK=NULL;$valorFEK=NULL;$interfasePadre = NULL;
     if (file_exists($variables->CAplicacion."modulos/".$_GET['dir'])){
       
         
         if (is_dir($variables->CAplicacion."modulos/".$_GET['dir'])  ){
             
             
       $conDatosEdita = new db($variables, __baseDatos,"editar");
       $conDatosEdita->connect(true);
       $conDatosEdita->setEncoding();
       $conDatosEdita->db(true);
                
       if (!isset ($_GET['esAgrupador']))
           $_GET['esAgrupador']= 0;
           
       if ($_GET['esAgrupador']==1){
           $salida.=  $navegador->armar_mapa();
       }else{
           
           $salida.=$navegador->cerrarSesion();
           
           if(isset ($_POST['dependencia']))
                $salida.=  $navegador->armarInterfase($conDatosEdita,$_POST['dependencia']);
           else
               $salida.=  $navegador->armarInterfase($conDatosEdita);
       }
             
         }else{
               $salida.= $navegador->armar_migas();
            $procesa_xml = new xml_procesador($variables->CAplicacion."modulos/".$_GET['dir'],$variables->CAplicacion."lenguajes/".__lenguaje.".xml");
            $forma = new procesar_formas($procesa_xml,$variables->CAplicacion."modulos/".$_GET['dir'],$_GET['dir']);
            if ($_REQUEST['interfase'] == 2){
                $salida = "";
                if(isset ($_POST['llave'])){ $llave=$_POST['llave']; }
                if(isset ($_POST['valorLlave'])){  $valorLlave = $_POST['valorLlave']; }
                if(isset ($_POST['FEK'])){  $FEK = $_POST['FEK']; }
                if(isset ($_POST['ValorFEK'])){  $valorFEK = $_POST['ValorFEK']; }
                if(isset ($_POST['interfasePadre'])){  $interfasePadre = $_POST['interfasePadre']; }
                 $salida.=  $forma->construye_formulario($llave,$valorLlave,$FEK,$valorFEK,$interfasePadre);
            }else{
                $salida = $navegador->armar_migas();
                $salida.=$navegador->cerrarSesion();
                 $salida.=  $forma->construye_formulario($_POST['llave'],$_POST['valorLlave']);
            }
            }
     }else{
         $salida = $controles->div_error("No se encontro el archivo para cargar la forma","Archivo XML ".$variables->CAplicacion."modulos/".$_GET['dir']." No encontrado");
     }
    echo $salida;
    
?>
