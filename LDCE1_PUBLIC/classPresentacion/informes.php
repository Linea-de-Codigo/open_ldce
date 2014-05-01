<?php

if (isset($_REQUEST['fecha_inicio']) || isset($_POST['omitir'])){
    $_POST['omitir'] = $_REQUEST['omitir'];
    $_POST['fecha_inicio'] = $_REQUEST['fecha_inicio'];
    $_POST['fecha_fin'] = $_REQUEST['fecha_fin'];
   // $_POST['preguntar'] = $_REQUEST['preguntar'];
    if (isset($_POST['pregunta']))
        $_POST['pregunta'] = $_REQUEST['pregunta'];
    
    $_REQUEST['fecha_inicio'];
}

include (__superCore."classPresentacion/clase_informes.php");
require_once __superCore."classPresentacion/class.controlesJson.php";

if (isset($_REQUEST['filtro']))
    $filtro = $_REQUEST['filtro'];

$id_informe=$_GET['INF'];
$mi_informe = new informes_nodriza();

$controles_usuario = new controles();

  function filas($tabla,$filtro,$db = 0){
         // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000BDA begin
        $globales = new cargar_variables();
        $conDatosEdita = new db($globales, __baseDatos,"editar");
       $conexion = $conDatosEdita->connect(true);
          
       $conexion = $conDatosEdita->db(true);
       $conexion = $conDatosEdita->setEncoding();
       $adminstrar_registros = new tabla($tabla);
       $buscarRegistro = $adminstrar_registros->consultarDatos("*",$filtro,"","","","", $conDatosEdita);
       return $buscarRegistro;
       // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000BDA end
    }

$este_informe = filas("informes",array("ID_INFORME"=>$id_informe));
 $pregunta = explode  (",",$este_informe[0]['preguntar']);
if(!$_POST){
?>
<form method="post" action="">
<table align="center" width="100%">
    <? if ($este_informe[0]['fecha'] == 1){ ?>
    <thead>
    
    <tr>
        <td>Fecha Inicio</td><td><? echo $controles_usuario->campo_fecha("fecha_inicio",""); ?></td>
        <td>Fecha Fin</td><td><? echo $controles_usuario->campo_fecha("fecha_fin",""); ?></td>
    </tr>
    </thead>
    <? } ?>
    <tr> 
    <? if ($este_informe[0]['fecha'] == 1){ ?>
        <td>Omitir Rango Fechas <input value="1" type="Checkbox" name="omitir" id="omitir"/> </td>
	<? } ?>
        <td><? if ($este_informe[0]['preguntar']){
                       if ($este_informe[0]['jsonConfig']==""){
                         echo $pregunta[1]." ".$controles_usuario->campo_texto("pregunta", "", 10);
                       }else{
						   $objetoCampo = json_decode($este_informe[0]['jsonConfig']);
						   
						   $control = new controlesJson("pregunta",$objetoCampo->tipo,$objetoCampo->configuracion,1,"");
						   echo  $pregunta[1]." ".$control->escribeCampo();
						}
                    }?></td>
        <td colspan="2"><? echo $controles_usuario->boton("ver", "Ver Informe", "verInforme()","") ?></td>
    </tr>
</table>
</form>
<? }
if($_POST){ 
if (!$_REQUEST['fecha_inicio'] && !$_REQUEST['omitir']){  ?>
<div style="overflow:auto; width: 850px; height: 450px">
<? } ?>
    <table width="95%" align="center">
        <tr>
            <td width="75%"><h1><? echo $este_informe[0]['DES_INFORME']  ?></h1></td><td>
                <!--<a href="sistema/includes/informes.php?INF=<?=$_GET['INF'] ?>&omitir=<?=$_POST['omitir'] ?>&pregunta=<?=$_POST['pregunta'] ?>&fecha_inicio=<?=$_POST['fecha_inicio'] ?>&fecha_fin=<?=$_POST['fecha_fin'] ?>" target="_blank"><img src ="images/imprimir.jpg" alt="Vista de Impresion" title="Vista de Impresion" border="0" width="60" /></a>-->
                <a href="informe.xls"><img title="Exportar a Excel" src ="images/ic_excel.gif" alt="Exportar a Excel" border="0" /></a
                ><? if($este_informe[0]['tipo']==2){ ?><a href="?dir=66&INF=<?=$_GET['INF'] ?>&omitir=<?=$_POST['omitir'] ?>&pregunta=<?=$_POST['pregunta'] ?>&fecha_inicio=<?=$_POST['fecha_inicio'] ?>&fecha_fin=<?=$_POST['fecha_fin'] ?>&npregunta=<?=$pregunta[0] ?>"><img src="images/LineChart.png" alt="Grafico de Lineas" title="Grafico de Lineas"> </a><? } ?>
                  <? if($este_informe[0]['tipo']==3){ ?><a href="?dir=119&INF=<?=$_GET['INF'] ?>&omitir=<?=$_POST['omitir'] ?>&pregunta=<?=$_POST['pregunta'] ?>&fecha_inicio=<?=$_POST['fecha_inicio'] ?>&fecha_fin=<?=$_POST['fecha_fin'] ?>&npregunta=<?=$pregunta[0] ?>"><img src="images/BarChart.png" alt="Grafico de Barras" title="Grafico de Barras"> </a><? } ?>
		  <? if($este_informe[0]['tipo']==1){ ?><a href="?dir=74&INF=<?=$_GET['INF'] ?>&omitir=<?=$_POST['omitir'] ?>&pregunta=<?=$_POST['pregunta'] ?>&fecha_inicio=<?=$_POST['fecha_inicio'] ?>&fecha_fin=<?=$_POST['fecha_fin'] ?>&npregunta=<?=$pregunta[0] ?>"><img src="images/PieChart.png" alt="Grafico de Barras" title="Grafico de Torta"> </a><? } ?>

            </td></tr></table>


<?
$filtro="";$filtro1="";
if ($_POST['omitir']=="false"){
    $filtro.= " where fecha >= '".$_POST['fecha_inicio']."' and fecha <= '".$_POST['fecha_fin']."'";
    $filtro1.= " where fecha <= '".$_POST['fecha_inicio']."'";
}

if (isset( $_POST['pregunta'])){
    if (!$_POST['omitir']){
        $filtro.= " and ";
	 $filtro1.= " and ";
    }else{
        $filtro.= " where ";
	$filtro1.= " where ";
}
	
	$filtro.= $pregunta[0]." like '".$_POST['pregunta']."%'";
	$filtro1.= $pregunta[0]." like '".$_POST['pregunta']."%'";
}


//echo $filtro;
echo "<div >";
echo $archivo = $nevo_informe = $mi_informe-> entrada_informe($id_informe,$filtro, $filtro1);//(1," where cta_efecta like  '".$_POST['cta']."%'","Informe Auxuliar",$_SESSION['usua'],Date("Y-M-d
echo "</div>";
$fp = fopen("informe.xls","w+");
$write = fputs($fp, $archivo);
fclose($fp);

?>

</div>
<? 
} ?>
