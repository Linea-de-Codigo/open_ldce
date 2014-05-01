<div style="padding: 10px;">
<?php
$datosModulo = new compatibilidadDB();

$filaModulo = $datosModulo->arrayRegistros("modulo", "*", " where idModulo = ".$_REQUEST['form']);
$filaProceso = $datosModulo->arrayRegistros("proceso", "*", " where idProceso = ".$filaModulo[0]['idProceso']);
$filaComponente = $datosModulo->arrayRegistros("componente", "*", " where idComponente = ".$filaProceso[0]['idComponente']);

$tablaKey = NULL;

for($i=0; $i<strlen($filaModulo[0]['tabla']); $i++)
  $tablaKey+=ord($filaModulo[0]['tabla'][$i]);

$campoKey = NULL;

for($i=0; $i<strlen($filaModulo[0]['llavePrimaria']); $i++)
  $campoKey+=ord($filaModulo[0]['llavePrimaria'][$i]);

$formaKey = NULL;

for($i=0; $i<strlen($filaModulo[0]['nombreForma']); $i++)
  $formaKey+=ord($filaModulo[0]['nombreForma'][$i]);

 $llaveEncriptada = md5(($campoKey * $tablaKey)-$formaKey);


$ubicarCarpeta = $filaComponente[0]['carpetaDesarrollo'];

echo "Obteniendo Ruta Absoluta del Modulo<br />";

$filaPadre = $datosModulo->arrayRegistros("proceso", "*", " where idProceso = ".$filaProceso[0]['idProcesoPadre']);

if (isset ($filaPadre[0]['carpeta'])){
    $ubicarCarpeta.="/".$filaPadre[0]['carpeta'];
}
    $ubicarCarpeta.="/".$filaProceso[0]['carpeta'];

    
if (!file_exists($ubicarCarpeta))
    die("Carpeta No existe no se puede crear el Modulo, revise si ya compilo los Proceso para este modulo");

echo $ubicarCarpeta."<br />";

$arrayAcciones = explode("|", $filaModulo[0]['accionesPermitidas']);
$cadenaAcciones = NULL;
foreach ($arrayAcciones as $key => $value) {
    $arrayValores = explode(":", $value);
    if ($arrayValores[0])
        if ($arrayValores[1] == "true")
            $cadenaAcciones.= " ".$arrayValores[0]."r=\"1\"";
            else
            $cadenaAcciones.= " ".$arrayValores[0]."r=\"0\"";
}

$contenidoArchivo = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<formularios >
\t<formulario clases = \"".$filaModulo[0]['clases']."\" id = \"1\" columas = \"".$filaModulo[0]['noColumnas']."\" ancho = \"".$filaModulo[0]['ancho']."%\"  >
\t\t<titulo>".$filaModulo[0]['tituloModulo']."</titulo>
\t\t<datos fechador = \"".$filaModulo[0]['fechador']."\" tablaLista = \"".$filaModulo[0]['tablaLista']."\" tabla = \"".$filaModulo[0]['tabla']."\" llave = \"".$filaModulo[0]['llavePrimaria']."\" $cadenaAcciones  />
\t\t<auditoria dependenciaRecursiva = \"".$filaModulo[0]['dependenciaRecursiva']."\" campo =\"".$filaModulo[0]['campoAuditoria']."\" estados = \"".$filaModulo[0]['estadosAuditoria']."\"  permitidos = \"".$filaModulo[0]['permitidosAuditoria']."\" titulo = \"".$filaModulo[0]['tituloAuditoria']."\" />\n";

$obtenerListaCampos = $datosModulo->arrayRegistros("campo", "*", " where idModulo = ".$filaModulo[0]['idModulo']." order by orden");
$autonumerico= NULL; $valores=NULL; $titulos =NULL; $filas = NULL; $columnas = NULL; $tomarLista = NULL;$recibe=NULL;$agrupador=NULL;

if (is_array($obtenerListaCampos))
    foreach ($obtenerListaCampos as $idCampo => $campo) {
        if ($campo['autonumerico']==1)        $autonumerico="autonumerico = \"1\"";    else        $autonumerico= NULL;
        if ($campo['valores']!="0" && $campo['valores']!="") $valores = "valores= \"".$campo['valores']."\""; else $valores = NULL;
        if ($campo['titulos']!="0" && $campo['titulos']!="") $titulos =   "titulos = \"".$campo['titulos']."\""; else $titulos = NULL;
        if ($campo['filas']!="0" && $campo['filas']!="") $filas =   "filas = \"".$campo['filas']."\""; else $filas = NULL;
        if ($campo['columnas']!="0" && $campo['columnas']!="") $columnas =   "columnas = \"".$campo['columnas']."\""; else $columnas = NULL;
        if ($campo['tomarLista']!="0" && $campo['tomarLista']!="" ) $tomarLista =   "tomarLista = \"".$campo['tomarLista']."\""; else $tomarLista = NULL;
        if ($campo['atributosExtra']!="0" && $campo['atributosExtra']!="") $atributosExtra =   "atributosExtra = \"".$campo['atributosExtra']."\""; else $atributosExtra = NULL;
        if ($campo['recibe']!="0" && $campo['recibe']!="") $recibe =   "recibe = \"".$campo['recibe']."\""; else $recibe = NULL;
        if ($campo['agrupador']!="0" && $campo['agrupador']!="") $agrupador = "agrupador= \"".$campo['agrupador']."\""; else $agrupador = NULL;


        $contenidoArchivo.= "\t\t<campo $atributosExtra $autonumerico $valores $titulos  $filas $columnas $agrupador  $tomarLista $recibe nombre = \"".$campo['nombreCampo']."\"  tipo = \"".$campo['tipoControl']."\" validar = \"".$campo['validacion']."\" tamano = \"".$campo['tamano']."\" ancho = \"".$campo['ancho']."\"  listar = \"".$campo['listar']."\"  guardar = \"".$campo['guardar']."\" >".$campo['tituloCampo']."</campo>\n";
    }

$otieneListaGrilla = $datosModulo->arrayRegistros("grilla", "*", " where idModulo = ".$filaModulo[0]['idModulo']);


$administrarGrilla = NULL;
if (is_array($otieneListaGrilla))
foreach ($otieneListaGrilla as $key => $grila) {
    
    if($grila['adminstrarModulo']!=0){
        $obtenerFormaAdministra = $datosModulo->arrayRegistros("modulo", "nombreForma", " where idModulo = ".$grila['adminstrarModulo']);
        $administrarGrilla = "formaAdministra = \"".$obtenerFormaAdministra[0]['nombreForma'].".xml\"";
    }else{
        $administrarGrilla = NULL;
    }
        
        $contenidoArchivo.="\t<grilla estilos = \"".$grila['estiloColumna']."\"  llavePrimaria=\"".$grila['llavePrimaria']."\" tabla = \"".$grila['tablaGrilla']."\" titulo = \"".$grila['titulo']."\" llave =\"".$grila['llaveGrilla']."\" campos = \"".$grila['campos']."\" $administrarGrilla />\n";
}
        
    $contenidoArchivo.="\t</formulario>\n\t<fechaCompilacion>".date("Y-m-d")."</fechaCompilacion>\n\t<descripcion>".$filaModulo[0]['descripcionModulo']." </descripcion>\n\t<key>$llaveEncriptada</key>\n\t<autor>RedCampo Ltda</autor>\n\t<codigoModulo>".$filaModulo[0]['codigo']."</codigoModulo>\n</formularios>\n ";
    
$archivo = fopen($ubicarCarpeta."/".$filaModulo[0]['nombreForma'].".xml", "w");


fwrite($archivo, $contenidoArchivo ); 

fclose($archivo);

echo "Proceso Creado exitosamente.";


?>

<div>Contenido del Archivo</div>
<div><textarea  wrap="off"  style ="width: 800px; height:  360px" rows ="16" id ="resultado" name ="resultado"><?=$contenidoArchivo; ?></textarea></div>
</div>