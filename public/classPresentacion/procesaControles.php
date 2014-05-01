<?
include(__superCore.'compartido/class.cargar_variables.php');
include(__superCore.'compartido/class.xml_procesador.php');
include(__superCore.'classNegocio/tabla.php');
require_once __superCore.'classPresentacion/class.controles.php';

$globales = new cargar_variables();
 $ConDatos = new db($globales, __baseDatos,"editar");
   $conexion = $ConDatos->connect(true);
   $conexion = $ConDatos->db(true);
   $conexion = $ConDatos->setEncoding();
   $adminstrar_registros = new tabla($_GET['tabla']);
   
if (!$_GET['mos'])
    $_GET['mos'] = 0;

if ($_REQUEST['filtro']!="")
    $filtroSecundario = " and ".$_REQUEST['filtro'];
    else
    $filtroSecundario="";

                $campos = "DISTINCT `".$_GET['enlace']."`, `".$_GET['mostrar']."` as mostrar";
                
                if ($_GET['mos']==0)
                    $filtro = array($_GET['mostrar'] =>$_GET['dato']);
                    else
                    $filtro = array($_GET['enlace'] =>$_GET['dato']);
                    
                $row_reg = $adminstrar_registros->consultarDatosSimil($campos,$filtro,"",0,40,$filtroSecundario, $ConDatos);
               
		if ($_GET['mos']==0){
			//$sql_reg = "select  from ".$_GET['tabla']." where ".."  like '%".."%' ".$_GET['filtro']." LIMIT 0 , 30";
                               
                if ($row_reg){

                $n=0;
		foreach ($row_reg as $key => $value) {
                    $n++;
		echo "<div id = \"line_$n".$_GET['nombre']."\" style = \"text-align:left;border-bottom: dotted 1px #cccccc; cursor:pointer;font-size:10px;\" onmousemove = \"document.getElementById('".$_GET['nombre']."_m').value = '".$value['mostrar']."'; document.getElementById('".$_GET['nombre']."').value = '".$value[$_GET['enlace']]."';\">".substr($value['mostrar'],0,85)."</div> ";
		}
                }else{
                        echo "No se encontro el elemeto";
                }

		}
                if ($_GET['mos']==1){
                
		
               if ($row_reg){
                     $n=0;
		foreach ($row_reg as $key => $value) {
                    $n++;
			echo "<div id = \"line_$n".$_GET['nombre']."\" style = \"cursor:pointer;font-size:10px;\" onmousemove = \"document.getElementById('".$_GET['nombre']."_m').value = '".$value['mostrar']."'; document.getElementById('".$_GET['nombre']."').value = '".$value[$_GET['enlace']]."';\">".substr($value['mostrar'],0,45)."</div> ";
		}
		}else{
                    echo "No se encontro el elemeto";
                }
                }

                if ($_GET['mos']==2){
			$sql_reg = "select DISTINCT ".$_GET['enlace'].", ".$_GET['mostrar']." as mostrar from ".$_GET['tabla']." where ".$_GET['mostrar']."  like '%".$_GET['dato']."%' ".$_GET['filtro']." LIMIT 0 , 30";
                        $rs_reg = mysql_query($sql_reg,$Con1) or die("<div>Error Nodriza 1.0, Favor Reportarlo al Administrador del Sistema<h1><img src = \"images/error_db.png\" />No se pudo obtener el conjunto de registros: </h1><br /><b>Tabla: </b> $tab <br /><b>Filtro Where:</b> $filtro</div><a href = \"documentacion/ver_faq.php?f=5\" rel=\"lightbox_text\">FAQ No. 5</a>");
			$row_reg = mysql_fetch_assoc($rs_reg);

                function mostrar_depende($id){
                    global $database_Con1, $Con1;
                    $sql_reg = "select DISTINCT ".$_GET['enlace'].", ".$_GET['mostrar']." as mostrar  from ".$_GET['tabla']." where ".$_GET['enlace']." =  '$id'";
			$rs_reg = mysql_query($sql_reg,$Con1) or die("<div>Error Nodriza 1.0, Favor Reportarlo al Administrador del Sistema<h1><img src = \"images/error_db.png\" />No se pudo obtener el conjunto de registros: </h1><br /><b>Tabla: </b> $tab <br /><b>Filtro Where:</b> $filtro</div><a href = \"documentacion/ver_faq.php?f=5\" rel=\"lightbox_text\">FAQ No. 5</a>");
			$row_reg = mysql_fetch_assoc($rs_reg);
                        $depende = $row_reg['depende'];
                        $n_s = 0;
                        $salida[$n_s]= "->".$row_reg['mostrar'];

                        while($depende){
                        $n_s++;
                            $sql_reg_ = "select DISTINCT ".$_GET['enlace'].", ".$_GET['mostrar']." as mostrar  from ".$_GET['tabla']." where ".$_GET['enlace']." =  '$depende'";
                            $rs_reg_ = mysql_query($sql_reg_,$Con1) or die("<div>Error Nodriza 1.0, Favor Reportarlo al Administrador del Sistema<h1><img src = \"images/error_db.png\" />No se pudo obtener el conjunto de registros: </h1><br /><b>Tabla: </b> $tab <br /><b>Filtro Where:</b> $filtro</div><a href = \"documentacion/ver_faq.php?f=5\" rel=\"lightbox_text\">FAQ No. 5</a>");
                            $row_reg_ = mysql_fetch_assoc($rs_reg_);
                            $salida[$n_s] = "->".$row_reg_['mostrar'];
                            $depende = $row_reg_['depende'];

                        };
                        for ($n_a = $n_s;$n_a>=0;$n_a--){
                            $salidas.= $salida[$n_a];
                        }
                    return $salidas;
                }

		do{
			echo "<div style = \"cursor:pointer;font-size:10px;\" onmousemove = \"document.getElementById('".$_GET['nombre']."_m').value = '".mostrar_depende($row_reg[$_GET['enlace']])."'; document.getElementById('".$_GET['nombre']."').value = '".$row_reg[$_GET['enlace']]."';\">".$row_reg[$_GET['enlace']]." | ".mostrar_depende($row_reg[$_GET['enlace']])."</div> ";
		}while($row_reg = mysql_fetch_assoc($rs_reg));
                }
?>
