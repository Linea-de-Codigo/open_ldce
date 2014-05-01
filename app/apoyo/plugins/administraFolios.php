<?php
$_GET['accion'] = "buscar";
$_GET['tabla']= "foliosDocumentos";
 $filtro = array("codigoDocumento"=>$_POST['nombre'].$_POST['id']);
 
$tipoCampo=$_POST['campo'];
$codigoDoc = $_POST['id'];
$nombreArchivo = $_POST['nombre'].$_POST['id'];
$_POST = array();
$_POST['*'] = "";
include("../../core/negocio/RecibeSolicitud.php");
$controles = new controles();

function muestra_fotos($foto,$carpeta){
    
    $result = file_get_contents($carpeta."/".$foto );
    
    return $foto;
}

?>
<div style ="float:left; ">Nombre del Archivo<input type ="text" id="archivo" mame = "Gu_archivo" value="<?=$nombreArchivo?>" /></div>
<div>
   <?=$controles->boton("lista","Ver Lista","document.getElementById('nuevoFolioBoton').style.display='none';document.getElementById('verListaBoton').style.display='';"); ?> 
    <?=$controles->boton("nuevoFolio","Nuevo Folio","document.getElementById('verListaBoton').style.display='none';document.getElementById('nuevoFolioBoton').style.display='';"); ?> 
</div>
<div id="verListaBoton" style="width:570px;height:300px;overflow: auto;border: dotted 1px #cccccc;margin-left: 5px;padding: 5px; ">
    <?
    
     if ($buscarRegistro){
            foreach ($buscarRegistro as $key1 => $value) { 
                echo muestra_fotos($value['archivo'],$globales->uploadDir."/poder");
         ?>
                
    <? 
            }
    }else{
        echo "No se encontro archivo";
    } ?>            
</div>

<div id="nuevoFolioBoton" style="display: none;overflow: auto;border: dotted 1px #cccccc;margin-left: 5px;padding: 5px;">
    
<? 
$_GET['dir'] = "clientes/subirFolios.xml";
$_POST['llave'] = NULL;
$_POST['valorLlave'] = NULL;
$_POST['FEK'] = NULL;
$_POST['ValorFEK'] = NULL;
$_POST['interfasePadre'] = NULL;



$_POST['interfase'] =2;
include("../../core/presentacion/indice_formas.php");?>
</div>