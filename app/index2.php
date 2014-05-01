<?php
require_once "info.jpg";
require_once  __superCore."compartido/class.cargar_variables.php";
require_once  __superCore."compartido/class.xml_procesador.php";
require_once __superCore."classPresentacion/class.controles.php";
require_once  __superCore."classNegocio/tabla.php";
require_once __superCore."classPresentacion/class.controles.php";
require_once "apoyo/plugins/compatibilidadDB.php";
require_once "apoyo/plugins/enLetras.php";

$globales = new cargar_variables();

if ($_REQUEST['carpeta'])
    require_once "apoyo/".$_REQUEST['carpeta']."/".$_REQUEST['archivo'].".php";
    else
    echo "No tiene acceso a este recurso";
?> 