<?php
require_once  __superCore."compartido/class.cargar_variables.php";
require_once  __superCore."compartido/class.xml_procesador.php";
require_once __superCore.'classPresentacion/class.procesar_formas.php';
require_once __superCore.'classPresentacion/class.controles.php';

$globales = new cargar_variables();


$procesa_xml = new xml_procesador($_GET['xml'],$globales->CAplicacion."lenguajes/".__lenguaje.".xml");
$forma = new procesar_formas($procesa_xml,$_GET['xml'],$_GET['dir']);
if($_GET['palabra']!=""){
    $filtro = array($_GET['parametro']=>$_GET['palabra']);
    
}
$filtroBusqueda=NULL;
if (isset($_GET['filtroBusqueda'])){  $filtroBusqueda = $_GET['filtroBusqueda']; }
if (isset ($filtro))
    $salida = $forma->construyeGrilla($filtro,1,$filtroBusqueda);
    else
   $salida = $forma->construyeGrilla("",1,$filtroBusqueda);     

echo $salida;

?>
