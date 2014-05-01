<?
error_reporting(E_ALL);
include("classEditaCampo.php");
if (!isset($_SESSION)) {   session_start(); }
if (0 > version_compare(PHP_VERSION, '5')) {
    die('Se Necesita PHP 5');
}


class procesar_formas{

    public $forma=null;


    private $xml=null;

    private $no_columnas=null;


    private $nombre_tabla=null;


    private $campos=null;

    private $ancho_tabla=null;


    private $nombre_formulario=null;


    private $control_datos=null;


    private $carpetaModulo=null;

    private $eHtml=NULL;
    
   

    private $tablaGrilla=NULL;


    private $tituloGrilla=NULL;


    private $llaveGrilla= NULL;

    private $xmlInfoModulo = NULL;
    private $camposGrilla = NULL;

    public $estilosGrilla = NULL;

    public $formaGrilla = NULL;
    

    private $rutaXML=NULL;
    
    private $idModulo=NULL;
    
    private $agruparGrilla;
    
    private $funcionGrilla = NULL;


    private $globales;

    function  __construct($xml,$archivoXML,$dir=NULL) {
         // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000A94 begin
	 
	 $this->globales = new cargar_variables();
        $this->rutaXML = $archivoXML;
        
        $lista = explode("/", $archivoXML);
         $cuantos = count($lista);
         $nuevaLista="";
        for ($n=0;$n<$cuantos-1;$n++){
            $nuevaLista.=$lista[$n]."/";
        }
        $lista = explode("/", $dir);
         $cuantos = count($lista);
         $nuevaListaA="";
        for ($n=0;$n<$cuantos-1;$n++){
            $nuevaListaA.=$lista[$n]."/";
        }
        $this->nuvaLista = $nuevaListaA;
        $infoModulo =  $nuevaLista."infoModulo.xml";

        $this->xmlInfoModulo = new xml_procesador($infoModulo);
        
        
        $this->xml = $xml;
        $this->no_columnas = $this->xml->atributos("formulario","columas");
        $this->nombre_tabla = $this->xml->atributos("datos","tabla");
        $this->ancho_tabla = $this->xml->atributos("formulario","ancho");
        $this->nombre_formulario = $this->xml->tag("titulo");
        $this->eHtml = $this->xml->tag("eHtml");
        
        $this->control_datos['buscar'] = $this->xml->atributos("datos","buscar");
	   $this->control_datos['tablaBuscar'] = $this->xml->atributos("datos","tablaBuscar");
        $this->control_datos['eliminar'] = $this->xml->atributos("datos","eliminar");
        $this->control_datos['guardar'] = $this->xml->atributos("datos","guardar");
        $this->control_datos['llave'] = $this->xml->atributos("datos","llave");
        $this->control_datos['autorecarga'] = $this->xml->atributos("datos","autorecarga");
         $this->control_datos['fechador'] = $this->xml->atributos("datos","fechador"); 
        
        
       /* $auxListaBuscar = explode("|",$this->xml->atributos("datos","listaBuscar"));
        
        if($auxListaBuscar[0]){
            $auxInterno = NULL;
            foreach ($auxListaBuscar as $idLista => $valorLista) {
                $auxInterno = explode(":",  $valorLista);
                $this->control_datos['listaBuscar'][$auxInterno[0]] = $auxInterno[1];            
            }
        }
        
        //echo $this->control_datos['listaBuscar']['Nombre Hacienda'];
        
        unset ($auxListaBuscar);
        unset ($auxInterno);*/
        
        $campos = $this->xml->tag("campo",1);
        $tipos = $this->xml->atributos("campo","tipo",1);

        
        $validar = $this->xml->atributos("campo","validar",1);
        $nombre = $this->xml->atributos("campo","nombre",1);
        $tomarLista = $this->xml->atributos("campo","tomarLista",1);
        $tamano = $this->xml->atributos("campo","tamano",1);
        $guardar = $this->xml->atributos("campo","guardar",1);
        $bandera = $this->xml->atributos("campo","bandera",1);
        
        $listar = $this->xml->atributos("campo","lista",1);
        $tagExtra = $this->xml->atributos("campo","tagExtra",1);
        $listaBuscar = $this->xml->atributos("campo","listar",1);

        
        $valoresLista = $this->xml->atributos("campo","valores",1);
        $titulosLista = $this->xml->atributos("campo","titulos",1);
        $recibe = $this->xml->atributos("campo","recibe",1);
        $autonumerico = $this->xml->atributos("campo","autonumerico",1);
        $carpeta = $this->xml->atributos("campo","carpeta",1);
        $valor = $this->xml->atributos("campo","valor",1);
        $ancho = $this->xml->atributos("campo","ancho",1);
        $columnas = $this->xml->atributos("campo","columnas",1);
        $filas = $this->xml->atributos("campo","filas",1);
        $separador = $this->xml->atributos("campo","separador",1);
        $this->tablaGrilla = $this->xml->atributos("grilla","tabla",1);
        $this->tituloGrilla = $this->xml->atributos("grilla","titulo",1);

	
	$auxLlaveGE = $this->xml->atributos("grilla","llaveExterna",1);
	$this->agruparGrilla = $this->xml->atributos("grilla","agrupar",1);
	
	if (is_array($this->xml->atributos("grilla","llave",1)))
		foreach ($this->xml->atributos("grilla","llave",1) as $keyLlave => $llaveGP){
			$this->llaveGrilla[$keyLlave] = array($llaveGP,$auxLlaveGE[$keyLlave]);
		}
	
	if (is_array($this->xml->atributos("grilla","campos",1) ))
		foreach ($this->xml->atributos("grilla","campos",1) as $key => $camposG){
			$auxListaBuscar[$key]  = explode("|",$camposG);
		}
        
        if(isset($auxListaBuscar[0])){
	foreach($auxListaBuscar as $keyB => $auxListarBusca){
            $auxInterno = NULL;
            foreach ($auxListarBusca as $idLista => $valorLista) {
                $auxInterno = explode(":",  $valorLista);
                $this->camposGrilla[$keyB][0][$auxInterno[0]] = $auxInterno[1];    
                $this->camposGrilla[$keyB][1][$auxInterno[1]] = $auxInterno[2];
            }
        }
	}
       // print_r($this->camposGrilla);
        //echo $this->control_datos['listaBuscar']['Nombre Hacienda'];
        
        unset ($auxListaBuscar);
        unset ($auxInterno);
        if (is_array($this->xml->atributos("grilla","estilos",1)))
		foreach ($this->xml->atributos("grilla","estilos",1) as $keyEs => $estilosG){
			$this->estilosGrilla[$keyEs] = explode("|",$estilosG);
		}
		
        $this->formaGrilla = $this->xml->atributos("grilla","formaAdministra",1);

        $this->funcionGrilla  = $this->xml->atributos("grilla","funcionGrilla",1);

        $this->carpetaModulo = $dir;
        $auxArchivoForma = NULL;
        $archivoForma = explode("/", $this->carpetaModulo);
            $cuantos = count($archivoForma);
            for($n=0;$n<$cuantos-1;$n++){
                $auxArchivoForma.= $archivoForma[$n]."/";
            }
            
            $variables = new cargar_variables();
            
            $procesa_xml = new xml_procesador($variables->CAplicacion."modulos/".$auxArchivoForma."/infoModulo.xml",$variables->CAplicacion."lenguajes/".__lenguaje.".xml");
            
            $this->idModulo =  $procesa_xml->tag("id");
            
       $n=0;
      foreach ($campos as $campo){
            $this->campos[$n]['titulo'] = $campo;
            $this->campos[$n]['separador'] = $separador[$n];
            $this->campos[$n]['tipo'] = $tipos[$n];
            $this->campos[$n]['validar'] = $validar[$n];
            $this->campos[$n]['nombre'] = $nombre[$n];
            $this->campos[$n]['tomarLista'] = $tomarLista[$n];
            $this->campos[$n]['tamano'] = $tamano[$n];
            $this->campos[$n]['guardar'] = $guardar[$n];
            $this->campos[$n]['bandera'] = $bandera[$n];
            $this->campos[$n]['listar'] = $listar[$n];
            $this->campos[$n]['valoresLista']= $valoresLista[$n];
            $this->campos[$n]['titulosLista']= $titulosLista[$n];
            $this->campos[$n]['tagExtra']= $tagExtra[$n];
            
            
             if($listaBuscar[$n]==1)
                $this->control_datos['listaBuscar'][$campo] = $nombre[$n];
            
            $this->campos[$n]['recibe'] = $recibe[$n];
            $this->campos[$n]['autonumerico'] = $autonumerico[$n];
            $this->campos[$n]['carpeta'] = $carpeta[$n];
            $this->campos[$n]['valor'] = $valor[$n];
            $this->campos[$n]['ancho'] = $ancho[$n];
            $this->campos[$n]['columnas'] = $columnas[$n];
            $this->campos[$n]['filas'] = $filas[$n];
            $n++;
      }
    // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000A94 end
    }

    public function construye_formulario($llave, $valorLlave, $fEK=null, $valorFEK = null,$interfasePadre=NULL )   {
        // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AAC begin
	
	if ($this->idModulo !=NULL && isset ($_SESSION['perfil'.__baseDatos])){
        $idProceso = $this->idModulo;
        
        
        
        $globales = new cargar_variables();
        
        $objetoDB = new db($globales, __baseDatos,"editar");
        $conexion = $objetoDB->connect(true);
        $conexion = $objetoDB->db(true);
        
            
        $adminstrar_registros = new tabla("permiso");
        $condicion = array("idModulo"=>$idProceso,"idPerfil"=>$_SESSION['perfil'.__baseDatos]);
        $filaPermiso = $adminstrar_registros->consultarDatos("*",$condicion,"","","","", $objetoDB); 
        
        
        if (!$filaPermiso)
            die("No tiene permiso para acceder a este modulo");
        
         $permiso = $filaPermiso[0]['acciones'];
        
         $auxpermisos = explode("|", $permiso);
        
         
        $arrayPermiso = NULL;
        
        foreach ($auxpermisos as $key => $permisoActual) {
            $value = explode( ":",$permisoActual);
            
            if ($key<=2)
                $arrayPermiso[$value[0]] = $value[1];
        
            
        }
        }else{
            $arrayPermiso["Busca"]="true";
            $arrayPermiso["Guarda"]="true";
            $arrayPermiso["Elimina"]="true";
        }
	
        $usoSeparador = NULL; $classTitulo = NULL;
        $filtro =NULL;
        $buscarRegistro = NULL;  $auxValor=NULL;
        $accionFormulario = "guardar";
        $filtroBusqueda=NULL;
        $tipo=NULL;
        if($llave!="undefined" && $llave!=""){
            $accionFormulario = "editar";
            //echo $llave;
            $_POST = null;
            $campos = array("*");
            $_GET['tabla'] = $this->nombre_tabla;
            $_GET['accion'] = "buscar";
            $filtro = array($llave=>$valorLlave);
            $tipo=0;
            include '../negocio/RecibeSolicitud.php';
        }else{
            foreach ($this->campos as $keys => $values) {
                 if ($values['valor']){
                    $auxValor= explode("$", $values['valor']);
                    if(isset ($auxValor[1])){
                        $buscarRegistro[0][$values['nombre']]= $GLOBALS[$auxValor[1]];
                    }else{
                        $buscarRegistro[0][$values['nombre']]= $auxValor[0];
                    }
                }else{
                    $buscarRegistro[0][$values['nombre']]=NULL;
                }
            }
        }
        
        $control =new controles();
        
        $camp = NULL; $salida = NULL; $acciones = null; $colspan = NULL; $_lista =  NULL; $cantidad = NULL; $valor = NULL;$eventoActualiza=NULL;
         if($interfasePadre!=null){
            $eventoActualiza="recargaGrilla('$fEK','$this->nombre_tabla','$interfasePadre','dependenciaList',$('$fEK').value); \n";
        }
        
        $salida.="<div align = \"left\" style = \"width:".$this->ancho_tabla.";\"><form id=\"formulario\" name=\"formulario\" method = \"post\">"."".
        $control->campo_oculto("accion",$accionFormulario)
        ;
        $salida.="<input type = \"hidden\" name = \"fileConf\" id = \"fileConf\" value = \"$this->carpetaModulo\">";
        $auxCant=0;
       if ($this->no_columnas!=0 || $this->no_columnas!= ""){
        
            $cantidad = $this->no_columnas;
       }else{
           $cantidad = 2;
       }
            $camp=0;

            if ($this->control_datos['llave'] == "" && $this->nombre_tabla != ""){
               $salida.=$control->div_error("No se definio llave para la tabla","Primary Key No definido","101");
            }
                $salida.=$control->campo_oculto("llave",$this->control_datos['llave']);
                
            if (isset($this->control_datos['autorecarga']))
                $salida.=$control->campo_oculto("autorecarga",$this->control_datos['autorecarga']);
            
                
            $Auxcombo = NULL;
            if ($this->control_datos['buscar']==1 && $arrayPermiso["Busca"]=="true"){
                $Auxcombo = $control->combo_rapido("comboBuscar",$this->control_datos['listaBuscar'],"");
                $filtroBusqueda.= "<span id = \"zonaBuscar\">".$Auxcombo.$control->campo_texto("cajaBuscar","",10,"onclick=\"this.value=''\"");
                $acciones.=$filtroBusqueda;
              // $acciones.= $control->boton("Buscar","Buscar","cargarBusqueda('core/presentacion/procesaBusqueda.php','Buscar ".$this->nombre_formulario."',600,500,'','parametro='+this.form.comboBuscar.value+'&palabra='+this.form.cajaBuscar.value+'&tabla=".$this->control_datos['tablaBuscar']."&accion=buscar&xml=".$this->rutaXML."&dir=".$this->carpetaModulo."')");
                $acciones.= $control->boton("Lista","Lista","cargarBusqueda('core/presentacion/procesaBusqueda.php','Buscar ".$this->nombre_formulario."',1100,700,'','parametro='+this.form.comboBuscar.value+'&palabra='+this.form.cajaBuscar.value+'&filtroBusqueda=1&tabla=".$this->control_datos['tablaBuscar']."&accion=buscar&xml=".$this->rutaXML."&dir=".$this->carpetaModulo."')")."</span>";
            }
            if ($this->control_datos['guardar']==1 && $arrayPermiso["Guarda"]=="true"){
                
		if ($interfasePadre==null)
			$acciones.= $control->boton("Nuevo","Nuevo","nuevoRegistro('$this->carpetaModulo');");
		
		$acciones.= $control->boton("Guardar","Guardar","guarda_formulario(this.form,'$this->nombre_tabla',this.form.accion.value,this.form.llave.value,'$this->carpetaModulo');$eventoActualiza");
		if ($arrayPermiso["Elimina"]=="true" && $this->control_datos['eliminar']==1 )
			$acciones.= $control->boton("Eliminar","Eliminar","eliminarRegistro('$this->nombre_tabla','".$this->control_datos['llave']."','$valorLlave');");
                
            }
	    date_default_timezone_set("America/Bogota");
	    $fecha = time (); 
            $salida.="<div  style = \"width: $this->ancho_tabla px;\"><div class = \"tituloFormulario\" style=\"float: left;padding-left:10px;\"><h2>$this->nombre_formulario</h2></div><div align= \"right\" id = \"botonesPanel\">$acciones<span class = \"ahora\" >".date ( "h:i" , $fecha ) ."</span></div></div>";
            unset($Auxcombo);
            unset ($acciones);
            unset($filtroBusqueda);
            $predeterminar=NULL;
            
            $salida.="<p  class = \"notas\">*Requerido</p>\n<div id = \"campos\">";
           
              for ($n=0;$n<count($this->campos);$n++){
            
                if ($this->campos[$camp]['listar'] == 1)
                    $_lista.= $this->campos[$camp]['nombre']."|";

                             
                    $tag=NULL;
                    
                    if ($camp<count($this->campos)){
                         if($this->campos[$camp]['tipo']=="separador"){
                          $salida.="<div style = \"clear:both;\">"; 
                          if ($usoSeparador == 1){ $salida.= "</div>"; }
                          $salida.="</div><div class=\"separador\">".$this->campos[$camp]['titulo']."</div><div class = \"seccion\">";
                          $usoSeparador = 1;
                          $camp++;
                          continue 1;
                      }
		      if($this->campos[$camp]['tipo']=="div"){
                          $salida.="<div style = \"clear:both;\"></div>"; 
                          $salida.="<div id=\"".$this->campos[$camp]['nombre']."\"> ".$this->campos[$camp]['titulo']."</div>";
                          $camp++;
                          continue 1;
                      }
                        if ($this->campos[$camp]['titulo'] !="" && $this->campos[$camp]['titulo'] !=" "){
                            
                            if ($this->campos[$camp]['validar'])
                                $requerir = "*";
                            else 
                                $requerir = "";
                            
                            if($fEK==$this->campos[$camp]['nombre']){
                                $predeterminar= $valorFEK;
                                $tag="readonly=\"readonly\"";
                            }else{
				    if($this->campos[$camp]['tipo']!="titulo" && isset($buscarRegistro[0][$this->campos[$camp]['nombre']]))
					$predeterminar=$buscarRegistro[0][$this->campos[$camp]['nombre']];
					else
					$predeterminar="";
                            }
                            if ($this->campos[$camp]['autonumerico']==1 &&  $accionFormulario == "guardar"){
                                $this->campos[$camp]['guardar'] = 0;
                            }
                            if($this->campos[$camp]['tipo']=="oculto"){
                                $salida.=$this->prepara_control($camp,$predeterminar,$tag);
                            }else{
                                if($this->campos[$camp]['ancho']){
                                    $anchoCampo=$this->campos[$camp]['ancho'];
                                }else{
                                     $anchoCampo = 100/$this->no_columnas."%";
                                }
                                if (!isset($_SESSION['idDependenciaActual'.__baseDatos])) $_SESSION['idDependenciaActual'.__baseDatos] = NULL;
                                if ($this->campos[$camp]['nombre']=="idDependencia" && $_SESSION['idDependenciaActual'.__baseDatos] !=0){
                                    $this->campos[$camp]['tipo']="oculto";
                                    //$tag="readonly=\"readonly\" style  =\"background-image:none\"";
                                    $salida.="<div class=\"lineaFormulario\" style=\"width: $anchoCampo;  min-height:27px; float: left;\" ><div class = \"divCampo\" style=\"padding-left: 5px;padding-right: 5px;\"><label style=\"padding-left: 5px;padding-right: 5px;\"  for =\"".$this->campos[$camp]['nombre']."\"></label>
                                    <div style=\"float: right;\">".$this->prepara_control($camp,$_SESSION['idDependenciaActual'.__baseDatos],$tag)."</div></div></div>";
                                }else{
				 if($this->campos[$camp]['tipo']=="titulo"){ $classTitulo = "clasTitulo"; }else{ $classTitulo = "lineaFormulario";; }
                                $salida.="<div class=\"$classTitulo\" style=\"width: $anchoCampo;  min-height:27px; float: left;\" ><div class = \"divCampo\" style=\"padding-left: 5px;padding-right: 5px;\"><label style=\"padding-left: 5px;padding-right: 5px;\"  for =\"".$this->campos[$camp]['nombre']."\">".$requerir.$this->campos[$camp]['titulo']."</label>
                                <div style=\"float: right;\">".$this->prepara_control($camp,$predeterminar,$tag)."</div></div></div>";
                                } 
                                
                            }
                        }else{
                            $salida.=$this->prepara_control($camp);
                        }
                        
                    }
               
                unset ($tag);
                      
                       $camp++;
               
               }
               unset($predeterminar); unset ($cantidad);  unset ($colspan);
        $salida.="</div></div>".$control->campo_oculto("lista_buscar",$_lista)."</form>\n<div style = \"clear:both;\">";
        $salida.="<div id = \"respuesta\">&nbsp;</div>";
        unset ($_lista);
        $salida.=$this->eHtml;
        $filtro = NULL;
	
	if (is_array($this->tablaGrilla))
		foreach ($this->tablaGrilla as $key => $grilla_){
		   $filtro = array($this->llaveGrilla[$key][0]=>$buscarRegistro[0][$this->control_datos['llave']]);
		       $salida.=$this->construyeGrilla ($filtro,0,NULL,$key);
	       }
	       
       unset ($buscarRegistro);
       
        $salida.="</div>";
        $salida.="<div id = \"headers\"></div><div  align = \"center\">";
        return $salida;
         // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AAC end
    }


    function construye_tabla($info){
        // section 127-0-1-1--2bebd374:12fe0b8a32a:-8000:0000000000000AEE begin
        
        // section 127-0-1-1--2bebd374:12fe0b8a32a:-8000:0000000000000AEE end
    }

    public function prepara_control($campo, $valor, $tag = null)
    {
           // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AAE begin
        global $controles,$tam,$lista,$control,$filtro,$db,$nLista;
        $auxLista=null;$auxTitulo=null;
        $pref = "";
        $classe = NULL;
       $tagAuto=NULL;
        if ($this->campos[$campo]['guardar']==1){
                $pref = "Gu_";
                $classe = "class=\"".$this->campos[$campo]['validar']."\"";
        }
        if($this->campos[$campo]['autonumerico']==1){
            $tagAuto="readonly=\"readonly\"";
            $classe= "class=\"autonumerico\"";
            
        }
        
        $auxExtra = str_replace(":","=",$this->campos[$campo]['tagExtra']);
        
          $extra=$classe." ".$tag." ".$tagAuto." ".$auxExtra;   
        switch ($this->campos[$campo]['tipo']){
            case "texto":
                $control = $controles->campo_texto($this->campos[$campo]['nombre'],$valor,$this->campos[$campo]['tamano'],$this->campos[$campo]['bandera']." ".$extra,$pref);
                break;
             case "lista_ajax_doble":
                 $listas = $this->campos[$campo]['tomarLista'];
                 $lista = explode("|", $listas);
                $control = $controles->lista_ajax_doble($lista[0],$lista[2],$lista[1],$this->campos[$campo]['nombre'],14,$filtro,$valor,$db,$extra,$pref);
                break;
            case "lista_ajax":
                 $listas = $this->campos[$campo]['tomarLista'];
                 $lista = explode("|", $listas);
                 if (isset($lista[3]))
                     $filtro = "'".$lista[3]."='+$('".$lista[3]."').value";
                 
                 if ($this->campos[$campo]['tamano']=="")
                     $this->campos[$campo]['tamano'] = 12;
                 
                $control = $controles->lista_ajax($lista[0],$lista[2],$lista[1],$this->campos[$campo]['nombre'],$this->campos[$campo]['tamano'],$filtro,$valor,$db,$extra,$pref);
                break;
            case "listaTags":
                return controles::listaTags($this->campos[$campo]['nombre'],$valor,$pref,$this->campos[$campo]['separador']);
                break;
            case "fecha":
                 $control = $controles->campo_fecha($this->campos[$campo]['nombre'],$valor,$pref);
                break;
               case "hora":
                 $control = $controles->campo_hora($this->campos[$campo]['nombre'],$valor,$pref);
                break;
            case "boton_enviar":
                $_recibe = $lista = explode("|",$this->campos[$campo]['recibe']);
                $divRecibe = NULL;
                if (count($_recibe)==2){
                    $divRecibe = $_recibe[1];
                }
                $control = $controles->boton($this->campos[$campo]['nombre'],$this->campos[$campo]['nombre'],"enviar_formulario('modulos/".$this->carpetaModulo[0]."','".$_recibe[0]."',this.form,'".$divRecibe."')");
                break;
            case "combo":
	    
                $auxLista = explode("|", $this->campos[$campo]['valoresLista']);
                $auxTitulo = explode("|", $this->campos[$campo]['titulosLista']);
                $nLista="";
                foreach ($auxLista as $eLista => $id){
		
                    $nLista[$id] = $auxTitulo[$eLista];
                 }
                $control = $controles->combo_rapido($this->campos[$campo]['nombre'],$nLista,$valor,$pref,$extra);
                 break;
             case "archivo":
                 $control = $controles->campo_archivo($this->campos[$campo]['nombre'],$this->campos[$campo]['carpeta'],$valor,"apoyo/plugins/camaraWeb.php"); 
                 break;
             case "archivoPaginas":
                 $control = $controles->campo_archivo($this->campos[$campo]['nombre'],$this->campos[$campo]['carpeta'],$valor); 
                 break;
              case "oculto":
                $control = $controles->campo_oculto($this->campos[$campo]['nombre'],$valor,$pref);
                break;
            case "memo":
                $control = $controles->campo_memo($this->campos[$campo]['nombre'],$valor,$this->campos[$campo]['columnas'],$this->campos[$campo]['filas'],$pref);
                break;
            case "password":
                $control = $controles->campo_password($this->campos[$campo]['nombre'],$valor,0,$pref);
                break;
            case "grupo_check":
                //echo $this->campos[$campo]['valoresLista'];
                $auxLista = explode("|", $this->campos[$campo]['valoresLista']);
                $auxTitulo = explode("|", $this->campos[$campo]['titulosLista']);
                $control = $controles->grupo_check($this->campos[$campo]['nombre'],$auxLista,$auxTitulo,$valor,NULL,$pref);
                break;
            case "grupo_opciones":
                //echo $this->campos[$campo]['valoresLista'];
                $auxLista = explode("|", $this->campos[$campo]['valoresLista']);
                $auxTitulo = explode("|", $this->campos[$campo]['titulosLista']);
            
                $control = $controles->grupo_opciones($this->campos[$campo]['nombre'],$auxLista,$auxTitulo,$valor,"",$pref);
                      break;
		       case "titulo":
			$control ="";
		       break;
		case "div":
		
		$control  = "<div id = \"".$this->campos[$campo]['nombre']."\"></div>";
			break;
            default:
                $control = "Tipo no definido";
                break;
        }
        unset ($tagAuto);        unset ($extra);
        return $control;
        // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AAE end
    }


    public function construyeGrilla($filtro=NULL, $tipo=0,$filtroBusqueda=NULL,$keyG=0)
    {
        // section 127-0-1-1-3cca6f73:1308f4fe7f9:-8000:0000000000000BA5 begin
        
        $control = new controles();
        $salida = NULL;$key2=NULL;
        $cargaEditar = NULL;$campos=NULL; $xml = NULL; $Auxcombo= NULL; $textBusqueda = NULL; $tablaPrincipal  = NULL;$auxTipo=NULL;
        $control = new controles();
        if ($tipo==0){
            $titluloG = $this->tituloGrilla[$keyG];           
            $tablaG =  $this->tablaGrilla[$keyG];
            $camposSinComillas = $this->camposGrilla[$keyG][0];
		$tablaPrincipal = $this->tablaGrilla[$keyG];
            $llave = $this->llaveGrilla[$keyG][0];
             $anchoGrilla = "width:$this->ancho_tabla;";
	     $_GET['porPagina'] = 300;
        }else{
            $titluloG = "Lista de ".$this->nombre_formulario;
            $titulosCampos = $this->control_datos['listaBuscar'];
            $tablaG =  $this->control_datos['tablaBuscar'] ;
	    $tablaPrincipal = $this->nombre_tabla;
            $camposSinComillas = $this->control_datos['listaBuscar'];
            
            if (in_array("idDependencia", $camposSinComillas)){
                if($_SESSION['idDependenciaActual'.__baseDatos] !=0   )
                    $condicionAdicional=" idDependencia = ".$_SESSION['idDependenciaActual'.__baseDatos];
            }
            if ($this->control_datos['fechador']!=""){
                if ($condicionAdicional==NULL){ 
                    $condicionAdicional = " (".$this->control_datos['fechador']." = '0000-00-00' or (".$this->control_datos['fechador'].">='".$_SESSION['periodo'.__baseDatos]['fechaInicia']."' and ".$this->control_datos['fechador']."<='".$_SESSION['periodo'.__baseDatos]['fechaTermina']."'))"; ;
                }else{
                    $condicionAdicional.= " and ".$this->control_datos['fechador'].">='".$_SESSION['periodo'.__baseDatos]['fechaInicia']."' and ".$this->control_datos['fechador']."<='".$_SESSION['periodo'.__baseDatos]['fechaTermina']."'"; ;
                }
                //if ($light==NULL)
                    //$salida.="<div class=\"notas\"  align = \"right\" >Tenga en Cuenta que solo se mustran los registros que estan dentro del período actual</div>";
            }
            
            $llave = $this->control_datos['llave'];
            $anchoGrilla = "width:99%;";
            
        }
        if ($filtroBusqueda==1){
            $porPagina = 20;
              $Auxcombo = $control->combo_rapido("comboBuscar",$this->control_datos['listaBuscar'],"");
                $textBusqueda.= "<br /><form><span id = \"zonaBuscar\">".$Auxcombo.$control->campo_texto("cajaBuscar","",10,"onclick=\"this.value=''\"");
                if (isset ($_GET['controlSession'])){ //&controlSession=".$_GET['controlSession']."
                    $textBusqueda.= $control->boton("Buscar","Buscar","buscaInformacion('contenGrilla$tablaPrincipal$tipo$keyG','parametro='+this.form.comboBuscar.value+'&palabra='+this.form.cajaBuscar.value+'&controlSession=".$_GET['controlSession']."&tabla=".$this->nombre_tabla."&accion=buscar&xml=".$this->rutaXML."&dir=".$this->carpetaModulo."')")."";    
                }else{
                    $textBusqueda.= $control->boton("Buscar","Buscar","buscaInformacion('contenGrilla$tablaPrincipal$tipo$keyG','parametro='+this.form.comboBuscar.value+'&palabra='+this.form.cajaBuscar.value+'&tabla=".$this->nombre_tabla."&accion=buscar&xml=".$this->rutaXML."&dir=".$this->carpetaModulo."')")."";
                }
                
                $textBusqueda.=$control->boton("imprimeInfo", "Imprimir", "imprSelec('contenGrilla$tablaPrincipal$tipo$keyG');")."</span>";
                $salida.=$textBusqueda;
        }else{ $porPagina = 300; $_GET['porPagina'] = 300; }
        $auxArchivoForma = NULL;
        $archivoForma = explode("/", $this->carpetaModulo);
            $cuantos = count($archivoForma);
            for($n=0;$n<$cuantos-1;$n++){
                $auxArchivoForma.= $archivoForma[$n]."/";
            }
            $FEK=NULL;$valFEK=NULL;
            if ($filtro!=NULL)
                foreach ($filtro as $key => $value) {
                      $FEK = $key;
                      $valFEK = $value;
                }
                
                if ($tipo==0 && $this->formaGrilla[$keyG] != ""){
			$camposEditables = new editaCampo($this->globales->CAplicacion."modulos/".$auxArchivoForma.$this->formaGrilla[$keyG]);
                        if ($this->funcionGrilla[$keyG]!="") $funcion = $this->funcionGrilla[$keyG]; else $funcion = 'null';
                       $auxNuevo ="<div align=\"right\" style = \"padding:5px;\">".$control->boton("Agregar","Agregar","if ($('$FEK').value!=''){cargaEdicionGrilla('','".$auxArchivoForma.$this->formaGrilla[$keyG]."','','$FEK',$('$FEK').value,'$this->carpetaModulo',".$funcion.");}else{ alert('No hay ningun registro cargado'); }")."</div>";
                      
                }else{
                        $auxNuevo="<div style = \"clear:both\"></div>";
                        
                }
        $salida.="<div id = \"contenGrilla$tablaPrincipal$tipo$keyG\" class = \"grilla\" style =\"left; $anchoGrilla padding:5px;\" >";
        $salida.="<div id = \"Grilla$tablaPrincipal$tipo$keyG\"><div align = \"center\" style=\"float: left\"><h3>$titluloG</h3></div>";
        $salida.= $auxNuevo;
        $salida.="<div style = \"clear:both;\"></div><table class = \"Tabla$tablaPrincipal$tipo$keyG\" id = \"Tabla$tablaPrincipal$tipo\" align = \"center\" width = \"100%\"><thead><tr><td class = \"fila\"></td>";
     
      $n=0;
        foreach ( $camposSinComillas as  $TituloCampo => $NombreCampo) {
            
	    if (isset($this->camposGrilla[$keyG][1][$NombreCampo]) && $tipo==0)
		$auxTipo = $this->camposGrilla[$keyG][1][$NombreCampo];
		else
		$auxTipo = "texto";
		
	    if ($auxTipo!="oculto")
		   // if (isset ($this->estilosGrilla[$keyG][$n]))
			//$salida.="<th  style = \"".$this->estilosGrilla[$keyG][$n]."\">$TituloCampo</th>";
			//else
			$salida.="<th >$TituloCampo</th>"; 
         
		

            $campos[$TituloCampo]='`'.$NombreCampo.'`';
            $n++;
        
        }
        $salida.="</tr></thead><tbody>";
        $_POST = null;
        $_GET['tabla'] = $tablaG;
        $_GET['accion'] = "buscar";
        
        $estilosS=NULL;

        include  '../negocio/RecibeSolicitud.php';
        $auxAgrupador = NULL;
        $valorPantalla=NULL;
        if ($buscarRegistro){
            foreach ($buscarRegistro as $key1 => $value) {
	    
	    if ($this->agruparGrilla[$keyG]!="" && $tipo==0){
                         if ($value[$this->agruparGrilla[$keyG]]!=$auxAgrupador){
                             $auxAgrupador = $value[$this->agruparGrilla[$keyG]];
                             $salida.="<tr><td style = \"border-bottom:solid 1px;\" colspan = \"".count($camposSinComillas)."\">$auxAgrupador</td></tr>";
                         }
              }
	    
            $key2=$key1+1;
                    $salida.="<tr onmousemove = \"this.style.color ='#000000';\" onmouseout = \"this.style.background ='';\"><td class=\"listas fila\">".$key2."</td>";
                    foreach ($camposSinComillas as $key => $Valor) {
                        if ($Valor=="idDependencia"){
                                       $globales = new cargar_variables();
                                        $objetoDB = new db($globales, __baseDatos,"editar");
                                        $conexion = $objetoDB->connect(true);
                                        $conexion = $objetoDB->db(true);
                                        $adminstrar_registros = new tabla("dependencia");
                                        $condicion = array("idDependencia"=>$value[$Valor]);
                                        $filaDependencia = $adminstrar_registros->consultarDatos("*",$condicion,"","","","", $objetoDB);
                                          if (!$filaDependencia)
                                                $valorAEscribir = $value[$Valor];
                                                else
                                                $valorAEscribir = $filaDependencia[0]['nombre'];
                                                
                                }else{
                                    $valorAEscribir = $value[$Valor];
                                }
                        if ($tipo==0){
                            switch ($this->camposGrilla[$keyG][1][$Valor]){
                            case "archivo":
                                
                               if ( $value[$Valor]){
                                    $valorPantalla ="Ver Documento";
                                     $cargaEditar = "window.open('apoyo/descargaArchivo.php?archivo=".$valorAEscribir."','','width=100,height=20 0')";
                               }else{
                                    $valorPantalla ="No hay Archivo";
                                     $cargaEditar = "";
                               }

                                break;
                                case "archivoPaginas":
                                     $valorPantalla ="Editar P&aacute;ginas"; 
                                     $cargaEditar = "carga_div('apoyo/plugins/administraFolios.php','Administrar Folios',600,500,'nombre=".$this->camposGrilla[$keyG][$key][0]."&id=$value[$llave]&campo=$llave','get')";
                                    break;
				    case "oculto":
				     $valorPantalla = NULL;
				     $cargaEditar = NULL;
				    break;
				    case "editar":
                                
                                    $valorPantalla ="<div >".$camposEditables->escribeCampo($Valor, $value[$Valor],$this->llaveGrilla[$keyG][1],$value[$this->llaveGrilla[$keyG][1]])."</div>"; 
                                    $cargaEditar = "~";
				break;
				    
                            default :
                                
                                if ($this->formaGrilla[$keyG]!="")
                                    $cargaEditar = "cargaEdicionGrilla('".$this->llaveGrilla[$keyG][1]."','".$auxArchivoForma.$this->formaGrilla[$keyG]."','".$value[$this->llaveGrilla[$keyG][1]]."','$FEK','$valFEK','$this->carpetaModulo')";
                                
                                 $valorPantalla = $valorAEscribir;
                                  break;
                            }    
                        }else{
                            $cargaEditar = "cargaEdicion('".$llave."','".$tablaPrincipal."','$this->carpetaModulo','dependenciaList','".$value[$llave]."','".$auxArchivoForma.$this->xmlInfoModulo->tag("js",0)."','".$auxArchivoForma.$this->xmlInfoModulo->tag("css",0)."');cierra_box();";
                            $valorPantalla = $valorAEscribir;
                    }
		    
		    if (isset ($this->estilosGrilla[$keyG][$key])){ $estilosS = "style = \"".$this->estilosGrilla[$keyG][$key]."\""; }else { $estilosS  == NULL;}
			if($valorPantalla!=NULL)
				$salida.="<td $estilosS  class = \"listas\" ><div id = \"".$key1.$key."\" style = \"cursor:pointer;\" onclick = \"$cargaEditar\">".$valorPantalla."</div></td>";
                    }
                    $salida.="</tr>";
            }
	    }else{
		$salida.="<tr><td class = \"listas\" colspan = \"3\">No se encontro la Informacion Solicitada</td></tr>";
	    }
           unset ($FEK); unset($valFEK); 
        $salida.="</tbody></table></div>";
       
        $salida.="</div>";
        
        $arrayPaginas = null;
        
         if ($filtroBusqueda==1){
            
            $cantidadPaginas=ceil($totalRegistro/$porPagina);
            for ($y=1;$y<=$cantidadPaginas;$y++){
                $arrayPaginas[$y]=$y;
            }
            if (isset ($_GET['controlSession'])){
                $salida.="<div align = \"right\" style = \"padding:20px;\">En lista $porPagina de $totalRegistro Registros, Página ".$control->combo_rapido("comboPágina",$arrayPaginas,"","","onchange = \"buscaInformacion('contenGrilla$tablaPrincipal$tipo$keyG','parametro='+this.form.comboBuscar.value+'&palabra='+this.form.cajaBuscar.value+'&controlSession=".$_GET['controlSession']."&tabla=".$this->nombre_tabla."&accion=buscar&xml=".$this->rutaXML."&dir=".$this->carpetaModulo."&pagina='+this.value+'&porPagina=$porPagina')\"")."</div></form>";
            }else{
                $salida.="<div align = \"right\" style = \"padding:20px;\">En lista $porPagina de $totalRegistro Registros, Página ".$control->combo_rapido("comboPágina",$arrayPaginas,"","","onchange = \"buscaInformacion('contenGrilla$tablaPrincipal$tipo$keyG','parametro='+this.form.comboBuscar.value+'&palabra='+this.form.cajaBuscar.value+'&tabla=".$this->nombre_tabla."&accion=buscar&xml=".$this->rutaXML."&dir=".$this->carpetaModulo."&pagina='+this.value+'&porPagina=$porPagina')\"")."</div></form>";
            }
        }
        return $salida;
        // section 127-0-1-1-3cca6f73:1308f4fe7f9:-8000:0000000000000BA5 end
    }
}
?>
