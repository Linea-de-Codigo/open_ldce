<?php
if(isset ($_REQUEST['contenidoArchivo'])){
    $archivoM = fopen($_REQUEST['nombreArchivo'], "w");
    fwrite($archivoM, str_replace("<|--|>","+",(str_replace("<|-|>","&",$_REQUEST['contenidoArchivo'])))); 
    fclose($archivoM);
    echo "Guardado Correctamente";
    die();
}


if(!isset($_REQUEST['archivoModificar'])){
 $datosProceso = new compatibilidadDB();

$filaProceso = $datosProceso->arrayRegistros("proceso", "*", " where idProceso = ".$_REQUEST['idProceso']);
$filaComponente = $datosProceso->arrayRegistros("componente", "*", " where idComponente = ".$filaProceso[0]['idComponente']);

$moduloInicial = NULL;$inicial=NULL;
$carpetaPadre = NULL;

//echo "Buscando Padre...<br />";

$filaPadre = $datosProceso->arrayRegistros("proceso", "*", " where idProceso = ".$filaProceso[0]['idProcesoPadre']);


if (isset ($filaPadre[0]['carpeta'])){
    //echo $filaComponente[0]['carpetaDesarrollo']."/".$filaPadre[0]['carpeta'];
    if (!file_exists($filaComponente[0]['carpetaDesarrollo']."/".$filaPadre[0]['carpeta'])){
        die("Por Favor compile el Proceso padre: '".$filaPadre[0]['nombreProceso']."'");
    }else{
        $carpetaPadre = "/".$filaPadre[0]['carpeta'];
    }
}else{
    echo "No tiene Proceso Padre...<br />";
}



?>
     <iframe frameborder="0" width="100%" height ="99%" src="apoyo/modulos/modulo/editaArchivo.php?archivoModificar=<?=$filaComponente[0]['carpetaDesarrollo']."$carpetaPadre/".$filaProceso[0]['carpeta']."/".$_REQUEST['nombreArchivo'] ?>"></iframe> 
<? }else{ 
    ///echo $_REQUEST['archivoModificar>'];
   if (file_exists($_REQUEST['archivoModificar'])){
    $texto = "";
   
   $fp = fopen($_REQUEST['archivoModificar'],"r");
   //Leemos linea por linea el contenido del archivo
   while ($linea= fgets($fp,1024))
   {
      $texto .= $linea;
   }
}  else {
    echo "No existe";
}

    ?>
<script language="Javascript" type="text/javascript" src="editable.js"></script>
 <script type="text/javascript" src = "/apps/framework/javascript/mtls1.4.0.js"></script>
	<script language="Javascript" type="text/javascript">
		// initialisation
		editAreaLoader.init({
			id: "example_1"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: false
			,language: "es"
			,syntax: "js"
                        ,toolbar: "save, search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help, "
			,syntax_selection_allow: "css,html,js,php,python,xml"
                        ,save_callback: "my_save"
		});
                function my_save(id, content){
                
                var myRequest = new Request({
                    url: '../../../index2.php',
                    method: 'post',
                    //onRequest: function(){                       myElement.set('text', 'loading...');  },
                    onSuccess: function(responseText){
                        alert(responseText);
                    },
                    onFailure: function(){
                        alert("No se pudo guardar");
                    }
                });
                var contenidoFinal = content.replace(/\&/g,"<|-|>");
                var contenidoFinal = contenidoFinal.replace(/\+/g,"<|--|>");
                
                myRequest.send('directorio=modulo&archivo=editaArchivo&contenidoArchivo='+contenidoFinal+'&nombreArchivo=<?=$_REQUEST['archivoModificar'] ?>');
                

		}
	</script>
        
<form action='' method='post'>
<textarea id="example_1" style="height: 560px; width: 100%;" name="test_1">
<?=$texto; ?>

</textarea>
</form>
       
<? } ?>