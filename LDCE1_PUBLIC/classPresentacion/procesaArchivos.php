<?php
    $status = "";
require_once  __superCore."compartido/class.cargar_variables.php";
require_once  __superCore."compartido/class.xml_procesador.php";

$globales = new cargar_variables();
if(isset($_POST['control'])){
      // obtenemos los datos del archivo
        $tamano = $_FILES["archivo"]['size'];
        $tipo = $_FILES["archivo"]['type'];
        $archivo = $_FILES["archivo"]['name'];
        $prefijo = substr(md5(uniqid(rand())),0,6);
       
        if ($archivo != "") {
            // guardamos el archivo a la carpeta files
            $destino =  $_GET['carpeta']."/".$prefijo."_".$archivo;
	    //echo $globales->uploadDir;
            if (copy($_FILES['archivo']['tmp_name'],$globales->uploadDir.$destino)) {
                $status.="<script>window.parent.document.getElementById('Gu_".$_GET['nombre']."').value='$destino'</script>";
                $status.= "Archivo subido: Por favor Guarde el registro para actualizar el nombre del Archivo <b>".$archivo."</b>";
            } else {
                $status = "Error al subir el archivo";
            }
        } else {
            $status = "Error al subir archivo";
        }
    
    echo $status;
}  else {
if ($_GET['valor']){
    echo "<span style=\"color:#999999;font-size:10px;\"><a target = \"_blanck\" href = \"$globales->downloadDir".$_GET['valor']."\">".$_GET['valor']."</a></span>";
}
 ?>

<form method="post" enctype="multipart/form-data" action="">
    <input type ="file" size="5"  name="archivo" onchange="this.form.submit()" />
    <input type="hidden" value ="1" name="control" />
</form>
<? 

} ?>