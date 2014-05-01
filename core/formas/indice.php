<?php
    include "../compartido/clasesComunes.php";
    include "../classPresentacion/controles.php";
    $controles = new controles();
    include '../classPresentacion/ClassInterfase.php';
    $procesa_xml = new xml_procesador($_GET['dir']);
    $forma = new procesar_formas($procesa_xml);
    echo  $forma->construye_formulario();
?>