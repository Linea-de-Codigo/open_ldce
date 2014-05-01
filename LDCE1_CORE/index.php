<?php
include "compartido/clasesComunes.php";
include 'classPresentacion/ClassInterfase.php';
$variables = new cargar_variables();
$capturador_errores = new gestion_error();
$interfase_indice = new gestor_interfase("index.tpl",$variables->TituloAplicacion);
echo $interfase_indice->retorna_interfase();
?>