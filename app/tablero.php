<?php
 require_once "info.jpg";
require_once __superCore."/compartido/class.cargar_variables.php";
require_once __superCore."/compartido/class.xml_procesador.php";
require_once __superCore."/classPresentacion/class.navegacion";
require_once __superCore.'/classPresentacion/class.gestor_interfase.php';
require_once __superCore.'/classPresentacion/class.procesar_formas.php';
require_once __superCore.'/classPresentacion/class.controles.php';

$variables = new cargar_variables();

 $interfase_indice = new gestor_interfase("externa.tpl","",$variables->TituloAplicacion);
$interfase_indice->establecer_forma("tablero/asesores.xml");

echo $interfase_indice->retorna_interfase();
?>
