<?php
class editaCampo{
    var $formulario;
    var $nombreCampo;
    var $campos;
    var $nombre_tabla;
    function __construct($formulario){
        $this->formulario = new xml_procesador($formulario);;
        
    }
    function escribeCampo($nombreCampo,$valorCampo="",$llave="",$valorLlave=""){
        
        $this->nombreCampo = $nombreCampo;
        $this->nombre_tabla = $this->formulario->atributos("datos","tabla");
        $campos = $this->formulario->tag("campo",1);
        $tipos = $this->formulario->atributos("campo","tipo",1);
        $validar = $this->formulario->atributos("campo","validar",1);
        $nombre = $this->formulario->atributos("campo","nombre",1);
        $tomarLista = $this->formulario->atributos("campo","tomarLista",1);
        $tamano = $this->formulario->atributos("campo","tamano",1);
        $guardar = $this->formulario->atributos("campo","guardar",1);
        $bandera = $this->formulario->atributos("campo","bandera",1);
        $listar = $this->formulario->atributos("campo","lista",1);
        $listaBuscar = $this->formulario->atributos("campo","listar",1);
        $valoresLista = $this->formulario->atributos("campo","valores",1);
        $titulosLista = $this->formulario->atributos("campo","titulos",1);
        $recibe = $this->formulario->atributos("campo","recibe",1);
        $autonumerico = $this->formulario->atributos("campo","autonumerico",1);
        $carpeta = $this->formulario->atributos("campo","carpeta",1);
        $valor = $this->formulario->atributos("campo","valor",1);
        $ancho = $this->formulario->atributos("campo","ancho",1);
        $columnas = $this->formulario->atributos("campo","columnas",1);
        $filas = $this->formulario->atributos("campo","filas",1);
        $agrupador = $this->formulario->atributos("campo","agrupador",1);
        $atributosExtra = $this->formulario->atributos("campo","atributosExtra",1);
        
     if(is_array($campos))
      foreach ($campos as $key => $campo){
         if ($nombre[$key] == $this->nombreCampo ){
            $this->campos['titulo'] = $campo;
            $this->campos['tipo'] = $tipos[$key];
            $this->campos['validar'] = $validar[$key];
            $this->campos['nombre'] = $nombre[$key];
            $this->campos['tomarLista'] = $tomarLista[$key];
            $this->campos['tamano'] = $tamano[$key];
            $this->campos['guardar'] = $guardar[$key];
            $this->campos['bandera'] = $bandera[$key];
            $this->campos['listar'] = $listar[$key];
            $this->campos['valoresLista']= $valoresLista[$key];
            $this->campos['titulosLista']= $titulosLista[$key];
            $this->campos['atributosExtra']= $atributosExtra[$key];
            $this->campos['agrupador']= $agrupador[$key];
            $this->campos['recibe'] = $recibe[$key];
            $this->campos['autonumerico'] = $autonumerico[$key];
            $this->campos['carpeta'] = $carpeta[$key];
            $this->campos['valor'] = $valor[$key];
            $this->campos['ancho'] = $ancho[$key];
            $this->campos['columnas'] = $columnas[$key];
            $this->campos['filas'] = $filas[$key];
         }
      }
        
        return $this->prepara_control($valorCampo,$llave,$valorLlave);
    }
    
    private function prepara_control($valor,$llave,$valorLlave ,$tag = null)
    {
           // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AAE begin
        
        $eventoGuardado ="actualizaCampo($('".$this->campos['nombre']."__$valorLlave').value,'".$this->campos['nombre']."','$this->nombre_tabla','$llave','$valorLlave')";
        
        global $controles,$tam,$lista,$control,$filtro,$db,$auxLista,$auxTitulo,$nLista;
        $pref = "";
        $classe = NULL;
       $tagAuto=NULL;
        if ($this->campos['guardar']==1){
                $pref = "Gu_";
                if ($this->campos['validar']!="")
                    $classe = "class=\"".$this->campos['validar']."\"";
                
        }
        if($this->campos['autonumerico']==1){
            $tagAuto="readonly=\"readonly\"";
            $classe= "class=\"autonumerico\"";
        }
        
        $auxAtributos = explode("|",$this->campos['atributosExtra'] );
        $auxAtributo=NULL; $atributos = NULL;
        foreach ($auxAtributos as $key => $value) {
            $auxAtributo = explode(":", $value);
            if (isset ($auxAtributo[1]))
                $atributos.= $auxAtributo[0]."=\"".str_replace("`","'",$auxAtributo[1])."\" ";
        }
        
        $extra=$classe." ".$tag." ".$tagAuto." ".$atributos;   
        $this->campos['nombre'] = $this->campos['nombre']."__".$valorLlave;
        
        switch ($this->campos['tipo']){
            case "texto": 
                if ($valor == NULL && $classe == "class=\"numero\""){ $valor = 0; }
                $control = $controles->campo_texto($this->campos['nombre'],$valor,$this->campos['tamano'],$this->campos['bandera']." ".$extra." onchange = \"$eventoGuardado\"",$pref);
                break;
             case "lista_ajax_doble":
                 if ($this->campos['tamano']==0)
                     $this->campos['tamano']=12;
                 $listas = $this->campos['tomarLista'];
                 $lista = explode("|", $listas);
                $control = $controles->lista_ajax_doble($lista[0],$lista[2],$lista[1],$this->campos['nombre'],$this->campos['tamano'],$filtro,$valor,$db,$extra,$pref);
                break;
            case "lista_ajax":
                 $listas = $this->campos['tomarLista'];
                 $lista = explode("|", $listas);
                 if ($this->campos['tamano']==0)
                     $this->campos['tamano']=12;
                 if (isset($lista[3])){
                    $filtro = "' and ".$lista[3]." = ' + $('".$lista[3]."').value";
                 }
                     
                $control = $controles->lista_ajax($lista[0],$lista[2],$lista[1],$this->campos['nombre'],$this->campos['tamano'],$filtro,$valor,$db,$extra,$pref);
                break;
            case "fecha":
                 $control = $controles->campo_fecha($this->campos['nombre'],$valor,$pref);
                break;
            case "boton_enviar":
                $_recibe = $lista = explode("|",$this->campos['recibe']);
                $directorioModulo = $this->carpetaModulo[0];
                $divRecibe = NULL;
                if (count($_recibe)>=2){
                    $divRecibe = $_recibe[1];
                    if (count($_recibe)>=3){
                        $directorioModulo = $_recibe[2];
                    }
                }
                $control = $controles->boton($this->campos['nombre'],$this->campos['nombre'],"enviar_formulario('modulos/".$directorioModulo."','".$_recibe[0]."',this.form,'".$divRecibe."')");
                break;
            case "boton_cargar":
                $_recibe = $lista = explode("|",$this->campos['recibe']);
                $control = $controles->boton($this->campos['nombre'],$this->campos['nombre'],"if($('".$_recibe[2]."').value!=''){ carga_div('index2.php','',850,600,'directorio=".$_recibe[1]."&archivo=".$_recibe[0]."','form='+$('".$_recibe[2]."').value,'99') }else{ alert('Por favor ingrese ".$_recibe[2]."'); }");
                break;
            case "boton":

                $control = $controles->boton($this->campos['nombre'],$this->campos['nombre'],"",$extra);
                break;
            case "combo":
                $nLista=NULL;
                 $auxLista = explode("|", $this->campos['valoresLista']);
                $auxTitulo = explode("|", $this->campos['titulosLista']);
                foreach ($auxLista as $key => $eLista){
                    $nLista[$eLista] =  $auxTitulo[$key];
                 }
                 
                $control = $controles->combo_rapido($this->campos['nombre'],$nLista,$valor,$pref," onChange = \"$eventoGuardado\"");
                 break;
             case "archivo":
                 $control = $controles->campo_archivo($this->campos['nombre'],$this->campos['carpeta'],$valor,"apoyo/plugins/camaraWeb.php"); 
                 break;
             case "archivoPaginas":
                 $control = $controles->campo_archivo($this->campos['nombre'],$this->campos['carpeta'],$valor); 
                 break;
              case "oculto":
                $control = $controles->campo_oculto($this->campos['nombre'],$valor,$pref);
                break;
            case "memo":
                $control = $controles->campo_memo($this->campos['nombre'],$valor,$this->campos['columnas'],$this->campos['filas'],$pref);
                break;
            case "password":
                $control = $controles->campo_password($this->campos['nombre'],$valor,0,$pref);
                break;
            case "grupo_check":
                //echo $this->campos['valoresLista'];
                
                $auxLista = explode("|", $this->campos['valoresLista']);
                $auxTitulo = explode("|", $this->campos['titulosLista']);
                $control = $controles->grupo_check($this->campos['nombre'],$auxLista,$auxTitulo,$valor," onChange = \"seleccionarCheck(this,'".$this->campos['nombre']."');".$eventoGuardado."\"",$pref);
                break;
            case "grupo_opciones":
                //echo $this->campos['valoresLista'];
                $auxLista = explode("|", $this->campos['valoresLista']);
                $auxTitulo = explode("|", $this->campos['titulosLista']);
            
                $control = $controles->grupo_opciones($this->campos['nombre'],$auxLista,$auxTitulo,$valor,"",$pref);
                      break;
                  
            
            default:
                $control = "Tipo no definido";
                break;
        }
        unset ($tagAuto);        unset ($extra);
        return $control;
        // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AAE end
    }
    
    
}
?>