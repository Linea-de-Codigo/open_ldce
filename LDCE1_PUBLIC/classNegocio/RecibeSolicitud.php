<?php
require_once  __superCore."compartido/class.cargar_variables.php";
require_once  __superCore."compartido/class.xml_procesador.php";
require_once __superCore."classPresentacion/class.controles.php";
require_once  __superCore."classNegocio/tabla.php";
require_once  __superCore."classPresentacion/class.controles.php";



$globales = new cargar_variables();
$controlReasp = new controles();
if (!isset($condicionAdicional))
       $condicionAdicional=NULL;

if(!isset($tipo))
    $tipo=0;

$pagina = 0;
$auxFiltroUsuario = "";
$FiltroUsuario = "";
$porPagina = 20;

if(isset ($_GET['pagina']))
    $pagina=$_GET['pagina']-1;
  
if(isset ($_GET['porPagina']))
    $porPagina=$_GET['porPagina'];

   if ($_POST){
        $numero = count($_POST);
       $campos = array_keys($_POST); // obtiene los nombres de las varibles
       $valores = array_values($_POST);
       $envio = $_POST;
   }

   $conDatosEdita = new db($globales, __baseDatos,"editar");
   $conexion = $conDatosEdita->connect(true);
   $conexion = $conDatosEdita->db(true);
   $conexion = $conDatosEdita->setEncoding();
   $adminstrar_registros = new tabla($_GET['tabla']); 

   switch ($_GET['accion']){
       case "guardar":
        $insertar_registros = $adminstrar_registros->agregarDatosMinus($envio, $conDatosEdita);
        //$sql =   'SELECT last_value FROM "dependencia_idDependencia_seq"';
        //$consultaClave =  new tabla('`'.$_GET['tabla']."_".$_GET['llave']."_seq".'`');
        $buscarRegistro = $adminstrar_registros->consultarDatos("max(".$_GET['llave'].") as maximo ","","","","","", $conDatosEdita);   
        echo $insertar_registros[0]."&|&".$insertar_registros[1]."&|&".$buscarRegistro[0]['maximo'];
        break;

        case "editar":
        $condicion[$_GET['llave']] = $_POST[$_GET['llave']];
        $editar_registros = $adminstrar_registros->actualizarDatosMinus($envio,$condicion ,$conDatosEdita);
        echo $editar_registros[0]."&|&".$editar_registros[1];
        break;

        case "buscar":
        if ($campos){
            $camposB = implode(",", $campos);
	
	if (isset($_GET['controlSession'])){
		$auxFiltroUsuario = explode("|",$_GET['controlSession']);
	    if(empty($filtro)){
		
			$FiltroUsuario = " where ".$auxFiltroUsuario[0]." = ".$auxFiltroUsuario[1];
		}else{
			$FiltroUsuario = " and ".$auxFiltroUsuario[0]." = ".$auxFiltroUsuario[1];
		}
	}
        if ($condicionAdicional!=NULL){
            if($FiltroUsuario=="" && empty($filtro) ){
                $FiltroUsuario = " where ".$condicionAdicional;
            }else{
                $FiltroUsuario = " and ".$condicionAdicional;
            }
        }
	
            $dto = 1;
            if($tipo==1){
                $auxBuscarRegistro = $adminstrar_registros->consultarDatosSimil($camposB,$filtro,"",$pagina*20,$porPagina,$FiltroUsuario, $conDatosEdita,1);
                $buscarRegistro = $auxBuscarRegistro[0];
                $totalRegistro =  $auxBuscarRegistro[1];
            }else{
                $auxBuscarRegistro = $adminstrar_registros->consultarDatos($camposB,$filtro,"",$pagina*20,$porPagina,$FiltroUsuario, $conDatosEdita,1);
                $buscarRegistro = $auxBuscarRegistro;
            }
        }else{
            echo $controlReasp->div_msj("No se definieron Campos para buscar");
        }
        break;
        case "eliminar":
            $condicion[$_GET['llave']] = $_GET['valorLlave'];
            $eliminaRegistro = $adminstrar_registros->eliminarDatos($condicion,$conDatosEdita);
            echo $eliminaRegistro[0]."&|&".$eliminaRegistro[1];
        break;
        default :
        echo $controlReasp->div_msj("No se especifico accion");
   }
?>