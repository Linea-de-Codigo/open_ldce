<?php
include '../classNegocio/db.php';
include 'navegacion.php';
include "../compartido/clasesComunes.php";
$globales = new cargar_variables();

$interfase = new elementos_interfase;
echo $interfase->armar_mapa(1);
?>
