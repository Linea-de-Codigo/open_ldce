<?php
require_once  "/var/frameworkPHPRedCampo/compartido/class.cargar_variables.php";
require_once  "/var/frameworkPHPRedCampo/compartido/class.xml_procesador.php";
require_once  "../info.jpg";
$globales = new cargar_variables();

$archivo = explode("/", $_GET['archivo']);

copy($globales->uploadDir."/".$_GET['archivo'],'tmp/'.$archivo[1]); 

    $extensiones = array("jpg", "jpeg", "png", "gif","pdf");
    $f = 'tmp/'.$archivo[1];
    
    $ftmp = explode(".",$f);
    $fExt = strtolower($ftmp[count($ftmp)-1]);

    if(!in_array($fExt,$extensiones)){
        die("<b>ERROR!</b> no es posible descargar archivos con la extensión $fExt");
    }

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$f\"\n");
    $fp=fopen("$f", "r");
    fpassthru($fp);

unlink('tmp/'.$archivo[1]);
?>