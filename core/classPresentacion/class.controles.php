<?php
require_once  __superCore."classNegocio/tabla.php";
error_reporting(E_ALL);

/**
 * Clase que presenta y gestiona los diversos controles de la interdase.
 *
 * @author Dimar Borda
 * @since 01/05/2011
 * @version 1
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('Este componente requiere PHP 5');
}

/* user defined includes */
// section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000BD6-includes begin
// section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000BD6-includes end

/* user defined constants */
// section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000BD6-constants begin
// section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000BD6-constants end

/**
 * Clase que presenta y gestiona los diversos controles de la interdase.
 *
 * @access public
 * @author Dimar Borda
 * @since 01/05/2011
 * @version 1
 */

class controles{
      // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute
     *
     */
    

    // --- OPERATIONS ---

    /**
     * Accion que devuelve un copnjunto de filas para que el control muestre.
     *
     * @access public
     * @author Dimar Borda
     * @param  tabla
     * @param  filtro Filtro Where para especificar que filas se incluyen en la consulta
     * @param  db Identificador de la Base de datos 0Base de datos del usuario) 1(Base de datos global o de estadisticas).
     * @return mixed
     * @since 05/09/2010
     * @version 1
     */
    
    private function filas($tabla,$filtro,$db = 0){
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
     /**
     * Devuelve elconjunto de Filas de una tabla seleccioando que columnas de
     * tabala son ncesarias.
     *
     * @access public
     * @author Dimar Borda
     * @param  tabla Tabla de la Cual se van atomar los valores
     * @param  campos Lista de Campos separados por "," que intervienen en la consulta
     * @param  filtro Filtro Where de la consulta.
     * @param  db Base de datos de donde se toma la información 0(Base de datos de la emrpes) 1(Base de datos General o donde se almacenan las estadisticas generales)
     * @return mixed
     * @since 01/12/2010
     * @version 1
     */
    private function filas_campos($tabla,$campos,$filtro,$db = 0){
         // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C01 begin
       $globales = new cargar_variables();
       $conDatosEdita = new db($globales, __baseDatos,"editar");
       $conexion = $conDatosEdita->connect(true);
       $conexion = $conDatosEdita->db(true);
       $adminstrar_registros = new tabla($tabla);
       $buscarRegistro = $adminstrar_registros->consultarDatos($campos,$filtro,"","","","", $conDatosEdita);
       return $buscarRegistro;
       // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C01 end
    }
     /**
     * Permite generar Un combo(Select HTML) a partir de una tabla en la base de
     *
     * @access public
     * @author Dimar Borda
     * @param  tabla Tabla de donde se toma la Info para la lista
     * @param  mostrar Nombre del Campo que se va a mostrar en la lista.
     * @param  enlace Nombre del campo que se enlaza o clave principal de la tabla.
     * @param  filtro Filtro Where de la consulta.
     * @return mixed
     * @since 01/12/2010
     * @version 1
     */

	function combo_sencillo($tabla,$mostrar,$enlace,$filtro,$nombre,$evento){
        // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C10 begin
        
            	$filas_ = $this->filas_campos($tabla," $mostrar as mostrar, $enlace ",array($enlace => $valor));
		if($filas_==0){
			$salida = "No se han definido elementos en esta lista";
			}else{
			$salida = "<select name = \"$nombre\" id = \"$nombre\" onchange = \"$evento\" >
					<option selected >Seleccione</option>\n";
			do{
				$salida.="<option value = \"".$filas_[0][$enlace]."\">".$filas_[0]['mostrar']."</option>\n";
			}while($filas_[0] = mysql_fetch_assoc($filas_[1]));
			$salida.= "</select>\n";
		}
		return $salida;
          // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C10 end
	}
           /**
     * Para depreciar..... No usar.
     * 
     */
        function combo($mostrar,$valores,$nombre,$valor="",$evento=""){

            		$filas_ = $this->filas_campos($tabla," $mostrar as mostrar, $enlace ",array($enlace => $valor));
		
			$salida = "<select name = \"$nombre\" id = \"$nombre\" onchange = \"$evento\" >
					<option selected >Seleccione</option>\n";
			do{
				$salida.="<option value = \"".$valores."\">".$mostrar."</option>\n";
			}while($filas_[0] = mysql_fetch_assoc($filas_[1]));
			$salida.= "</select>\n";
		
		return $salida;
	}
          function campo_hora($nombre,$valor,$pref=""){
   // section 127-0-1-1-1e731379:1338937b600:-8000:0000000000000C8F begin
    $salida = null;
        if ($valor == "")
		$valor = date("H:i:s");
         
         $arayValores = explode(":", $valor);
        
        $salida.="<select name = \"hora_$nombre\" id = \"hora_$nombre\" onchange = \"$('$nombre').value = $('hora_$nombre').value+':'+$('minuto_$nombre').value+':00'\" > ";
        for ($index = 0; $index < 24; $index++) {
         if ($index==$arayValores[0]) $salida.="<option selected value = \"$index\">$index</option>";
                else  $salida.="<option value = \"$index\">$index</option>";
        }
        $salida.="</select>";
        $salida.="<select onchange = \"$('$nombre').value = $('hora_$nombre').value+':'+$('minuto_$nombre').value+':00'\" name = \"minuto_$nombre\" id = \"minuto_$nombre\" > ";
        for ($index = 0; $index < 60; $index+=15) {
         if ($index==$arayValores[0]) $salida.="<option selected value = \"$index\">$index</option>";
                else $salida.="<option value = \"$index\">$index</option>";
        }
        $salida.="</select>";
        
         
        $salida.= "<input type = \"hidden\"  value = \"$valor\" name = \"$pref$nombre\" id = \"$nombre\" />";
        
        return $salida;
    // section 127-0-1-1-1e731379:1338937b600:-8000:0000000000000C8F end
    }
        
         /**
     * Genera un select que tomna la informacion de un array mas no de la base
     * datos.
     *
     * @access public
     * @author Dimar Borda
     * @param  nombre Asigna Nombre del control select
     * @param  datos Array con los datos que contendra el select en el tag option atributo value
     * @param  valor Array con los valores que se muestran en cada option del Select
     * @param  pref Prefijo del Nombre del control.
     * @return mixed
     * @since 10/12/2011
     * @version 1
     */
        function combo_rapido($nombre,$datos,$valor,$pref= NULL,$evento = NULL){
           // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C1F begin
            $salida = NULL;
            $salida.="<select name = \"$pref$nombre\" id = \"$nombre\" $evento>";
            if (is_array($datos) || is_object($datos))
                foreach ($datos as $idDatos => $valorDatos) {
		if ($valorDatos==$valor)
                    $salida.="<option selected = \"selected\" $evento value = \"$valorDatos\">$idDatos</option>";
                    else
                    $salida.="<option value = \"$valorDatos\">$idDatos</option>";
                }
            $salida.= "</select>";
            return $salida;
            // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C1F end
        }

        /**
     * Genera una lista de valores seleccionables tomados de una tabla de la
     * de datos
     *
     * @access public
     * @author Dimar Borda
     * @param  tabla Nombre de la Tabla donde se Toman los datos
     * @param  mostrar Nombre del campo o array de campos q se deben mostrar en la lista.
     * @param  enlace Nombre del campo que es clave principal en la tabla y que va a enlazar.
     * @param  nombre Nombre del Control que se va enviar por post ene formulario
     * @param  tam Numero de caracteres q va a mostrar el copntrol
     * @param  filtro Filtro Where en la Consulta
     * @param  valor Valor Inicial del Control
     * @param  db Base de datos de donde toma la lista, 0 (Tabla del usuario), 1 (Base de datos del Sistema o Geenreal para estadisticas)
     * @param  tag Otro dato que se quiera colocar dentro del tag Input.
     * @param  pref prefijo del Nombre del control
     * @param  texto Texto Inicial del control cuando esta vacio. (Cuando "valor" es Nulo o vacio)
     * @return mixed
     * @since 12/12/2010
     * @version 1
     */
        function lista_ajax($tabla,$mostrar,$enlace,$nombre,$tam,$filtro,$valor,$db=0,$tag="",$pref="",$texto = ""){
        // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C35 begin
        
            if ($valor != "")
               $fila_mostrar = $this->filas($tabla,array($enlace=>$valor));

            if ($valor)
                $texto = $fila_mostrar[0][$mostrar];
            
            if ($filtro=="")
                 $filtro="''";
            
		$salida = "
		<input style=\"background-image:url(images/lupa.png); background-position: right center;padding-right:25px; background-repeat: no-repeat;\" autocomplete = \"off\" value = \"".$texto."\"  type = \"text\" size = \"$tam\" name = \"".$nombre."_m\" id = \"".$nombre."_m\" onkeyup = \"lista_ajax('$nombre',this.value,'$tabla','$mostrar','$enlace',$filtro,'','$db',event.keyCode)\" onblur = \"document.getElementById('salida_".$nombre."').style.display = 'none' \" $tag  />
		<input  value = \"$valor\" type = \"hidden\" name = \"".$pref.$nombre."\" id = \"".$nombre."\" /><input type = \"button\" onclick = \"$('".$nombre."').value = '';$(".$nombre."_m).value='';\" class = \"botonLista\" />
		<div id = \"salida_".$nombre."\" style = \"display:none;width:280px;height:120px; padding:5px;  box-shadow: 2px 2px 5px #000;position:absolute;background-color:#ffffff;overflow-x: hidden; overflow-y: scroll; \"></div>";
		return  $salida;
         // section 127-0-1-1--c11e6e5:13385070ca2:-8000:0000000000000C35 end
	} 
        
        
    /**
     * Short description of method lista_ajax_arbol: <b>En desarrollo.....</b>
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  tabla
     * @param  mostrar
     * @param  enlace
     * @param  nombre
     * @param  tam
     * @param  filtro
     * @param  valor
     * @param  tag
     * @param  tag
     * @return mixed
     */
	function lista_ajax_arbol($tabla,$mostrar,$enlace,$nombre,$tam,$filtro,$valor,$tag,$texto = ""){
        // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C35 begin
        
         if ($valor != "")
          $fila_mostrar = $this->filas($tabla," where $enlace = '$valor'");

         if ($valor)
                $texto = $fila_mostrar[0][$mostrar];

		$salida = "
		<input autocomplete = \"off\" value = \"".$texto."\"  type = \"text\" size = \"$tam\" name = \"".$nombre."_m\" id = \"".$nombre."_m\" onkeyup = \"lista_ajax('$nombre',this.value,'$tabla','$mostrar','$enlace','$filtro')\" onblur = \"document.getElementById('salida_".$nombre."').style.display = 'none'; \" $tag  />
		<input  value = \"$valor\" type = \"hidden\" name = \"".$nombre."\" id = \"".$nombre."\" />
		<div id = \"salida_".$nombre."\" style = \"display:none;width:280px;height:120px; padding:5px;  box-shadow: 2px 2px 5px #000; position:absolute;background-color:#ffffff;overflow-x: hidden; overflow-y: scroll; \"></div>";
		return  $salida;
                // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C35 end
	} 
        
            /**
     * Lista ajax con dos interfasces de busqueda y captura, una interfase
     * mostrar la clave principal de la tabla y la otra una columna descriptiva.
     *
     * @access public
     * @author Dimar Borda
     * @param  tabla Nombre de la tabla de donde se obtiene la lista.
     * @param  mostrar Nombre del campo que se va amostrar en la segunda interfase.
     * @param  enlace Nombre del campo llave principal en la tabla del primer parametro
     * @param  nombre Nombre e id del Control INPUT usado para el envio post o el control javascript.
     * @param  tam Tamaño del Control (size).
     * @param  filtro Filtro Where para seleccionar que filas se muestran en la lista.
     * @param  valor Valor del Control
     * @param  db Bse de datos 0 (Base de datos del Usuario), 1 (Base de datos Global y/o estadisticas)
     * @param  tag Otro valor para la etiqueta input ideal para asignar un atributo.
     * @param  pref Prefijo para el nombre del producto.
     * @return mixed
     * @since 10/10/2010
     * @version 1
     */
	 function lista_ajax_doble($tabla,$mostrar,$enlace,$nombre,$tam,$filtro,$valor,$db,$tag="",$pref=""){
             
        // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C40 begin
        $fila_mostrar = array(0,0);
        
        if ($valor=="")
            $valor = NULL;
        
       $fila_mostrar = $this->filas_campos($tabla,"$mostrar as mostrar, \"$enlace\"",array($enlace => $valor));
     
		$salida = "<script>linea = 0;</script>
         <input $tag autocomplete = \"off\" type = \"text\" class = \"buscar_ajax\" value = \"$valor\" size = \"7\" name = \"".$pref.$nombre."\" id = \"".$nombre."\"  onkeyup = \"lista_ajax('$nombre',this.value,'$tabla','$mostrar','$enlace','$filtro',1,'$db',event.keyCode)\" onblur = \"document.getElementById('salida_".$nombre."').style.display = 'none' \"/>
	 <input $tag autocomplete = \"off\" type = \"text\" class = \"buscar_ajax\" size = \"$tam\" value = \"".$fila_mostrar[0]['mostrar']."\"  name = \"".$nombre."_m\" id = \"".$nombre."_m\"  onkeyup = \"lista_ajax('$nombre',this.value,'$tabla','$mostrar','$enlace','$filtro','','$db',event.keyCode);this.focus();\" onblur = \"document.getElementById('salida_".$nombre."').style.display = 'none';linea=0; \"  />
		<div id = \"salida_".$nombre."\" style = \"display:none;width:280px;height:120px; padding:5px;  box-shadow: 2px 2px 5px #000; ;position:absolute;background-color:#ffffff;overflow-x: hidden; overflow-y: scroll; \"></div>";
		return  $salida;
	// section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C40 end
        }
    public static function listaTags($nombre,$valor,$pref="",$separador=","){
         $salida='<div id = "tag_'.$nombre.'" class="tagWrap" class="hide">
        <div class="left tagLock">';
         if ($separador==null)
            $objetoJson = json_decode($valor);
            else
            $objetoJson = explode ($separador, $valor);
                
         if (is_array($objetoJson))
            foreach ($objetoJson as $key => $value) {
                $salida.='<div class="tag">'.$value.'<span class="tagClose" id="close'.$key.'"></span></div>';
            }
        $salida.='</div>
        <div class="left">
            <input id="listTags"  value = "'.$valor.'"  name="listTags" placeholder="+Agregar" />
        </div>

        <div class="clear"></div>
    </div>';
        if ($separador==NULL) $salida.=controles::campo_oculto("json_$nombre", "1");
            else $salida.=controles::campo_oculto("json_$nombre", "0");
        
         $salida.= "<input type = \"hidden\" name = \"$pref$nombre\" id = \"$nombre\" value = '$valor' />";
         return $salida;
         
     }
        
    function campo_archivo($nombre,$carpeta,$valor=NULL,$camara=NULL,$rutaProcesador="core/presentacion/procesaArchivos.php",$tipoArchivos=NULL){
        // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C63 begin
        
        $globales = new cargar_variables();
        	//$filename = md5(date('YmdHisu')) . '.jpg';
		$filename =$valor;
        $salida=$this->campo_oculto("Gu_".$nombre, $filename);
        $salida.="<iframe name= \"$nombre\" frameborder=\"0\" src=\"$rutaProcesador?carpeta=$carpeta&nombre=$nombre&valor=$valor\" width=\"180\" height=\"65\" >
                 <p>Su Navegador no soporta Iframes.</p>
                 </iframe>" ;
                if ($camara!=NULL){
                    $salida.="<div id=\"camara_$nombre\" style = \"background-color:#ffffff;display:none;height:95%;width:97%;position:absolute;top:10px;left:10px;border:solid 1px;\"></div><div style=\"cursor:pointer;\" onclick = \"carga_div('$camara','titulo',840,750,'','nombre=$filename&carpeta=".$globales->uploadDir."$carpeta',1);\">Desde Camara</div>";
                }
        return $salida;
        // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C63 end
    }
    
    function campo_examinar($nombre,$valor=NULL,$carpeta=NULL,$tipo=1){
        // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C91 begin
        
        $salida = NULL;
        $cargaEditar=NULL;
        if($valor==NULL){
            $salida="<input type = \"file\" name \"$nombre\" id = \"$nombre\">";
        }else{
            if($tipo==1){
                $cargaEditar = "window.open('apoyo/descargaArchivo.php?archivo=".$valor."','','width=100,height=20 0')";
                $salida="<div onclick =\"$cargaEditar\">Descargar</div>";
                }
            
        }
        return $salida;
        // section 127-0-1-1-5816e322:13388b9dfb5:-8000:0000000000000C91 end
    }
    
    /**
     * Permite crear un Dialogo o mensaje indicando el nombre y el codigo del
     * que queremos expresar.
     *
     * @access public
     * @author RedCampo
     * @param  msj Mensaje Descriptivo del Error
     * @param  tecnico Mensaje Tecnico del error o mensaje entregado por el depurador
     * @param  cod Codigo del Erro basado en la politica.
     * @return mixed
     * @since 06/05/2011
     * @version 1
     */
    
    function div_error($msj,$tecnico="",$cod=100){
         // section 127-0-1-1--29585642:1338925ad2c:-8000:0000000000000C78 begin
        return   "<div id=\"mascara\"></div><div id = \"div_error\"  align = \"center\" >$msj error No. $cod<br /><div id  = \"error_tecnico\">$tecnico</div><br />".$this->boton("cerrar", "Cerrar", "document.getElementById('div_error').style.display = 'none';document.getElementById('mascara').style.display = 'none'").$this->boton("ayuda", "Ayuda", "carga_div('framework/documentacion/codErrorAyuda.php','Ayuda error No. - $cod',500,400)")." </div>";
        // section 127-0-1-1--29585642:1338925ad2c:-8000:0000000000000C78 end
    }
    
     /**
     * Permite crear un Dialogo o mensaje indicando
     *
     * @access public
     * @author Dimar Borda
     * @param  msj Mensaje q se quiera devolver
     * @return mixed
     * @since 2011/01/01
     * @version 1
     */
    function div_msj($msj){
        // section 127-0-1-1-1e731379:1338937b600:-8000:0000000000000C87 begin
        return   "<div id=\"mascara\"></div><div id = \"div_msj\"  align = \"center\" >$msj<br /><br />".$this->boton("cerrar", "Cerrar", "document.getElementById('div_msj').style.display = 'none';document.getElementById('mascara').style.display = 'none'")." </div>";
        // section 127-0-1-1-1e731379:1338937b600:-8000:0000000000000C87 end
    }
    
    
    /**
     * Permite crear un Input con el nombre del campo y un calendario para
     * la fecha.
     *
     * @access public
     * @author Dimar Borda
     * @param  nombre Nombre e Id del Input
     * @param  valor Valor del Control
     * @param  pref Prefijo del Nombre del campo
     * @return mixed
     * @since 12/05/2011
     * @version 1
     */
    function campo_fecha($nombre,$valor,$pref=""){
   // section 127-0-1-1-1e731379:1338937b600:-8000:0000000000000C8F begin
    if ($valor == "")
		$valor = date("Y-m-d");

        $salida = "<input type = \"text\" size = \"10\" value = \"$valor\" name = \"$pref$nombre\" id = \"$nombre\" />";
        $salida.="<button type=\"button\" class=\"calendarStyle\" id=\"fieldDateTrigger_".$nombre."\" onclick = \"if(document.getElementById('$nombre').value =='0000-00-00'){ document.getElementById('$nombre').value = '".date("Y-m-d")."' ;} displayCalendar(document.getElementById('$nombre'),'yyyy-mm-dd',this)\" />";
        return $salida;
    // section 127-0-1-1-1e731379:1338937b600:-8000:0000000000000C8F end
    }
    
    

    
    function campo_memo($nombre,$valor,$col=20,$filas=4,$pref=NULL){
        $salida = "<textarea name = \"$pref$nombre\" id = \"$nombre\" cols = \"$col\" rows = \"$filas\" >$valor</textarea>";
        return $salida;
    }
    public static function  campo_texto($nombre,$valor,$tam=10,$tag="",$pref=""){
        $salida = "<input type = \"text\" name = \"$pref$nombre\" id = \"$nombre\" value = \"$valor\" size = \"$tam\" $tag  />";
        return $salida;
    }

        function campo_texto_bloqueado($nombre,$valor,$tam){
        $salida = "<input type = \"text\" name = \"$nombre\" id = \"$nombre\" value = \"$valor\" size = \"$tam\" readonly />";
        return $salida;
    }
    public static function boton($nombre,$valor,$evento,$tag="",$icono = ""){

        if ($icono){
            $estilo = "style=\"width:110px; height:25px; border:transparent; cursor:pointer;  background-image: url('images/$icono'); background-repeat:no-repeat;\"";
            $valor = "";
        }else{
            $estilo = "class = \"boton\"";
        }

        
        $salida = "<button type = \"button\"  name = \"$nombre\" id = \"$nombre\"  onclick = \"$evento\"  $tag $estilo >$valor</button>";
        return $salida;
    }
    
        function submit($nombre,$valor,$evento,$tag="",$icono = ""){

        if ($icono){
            $estilo = "style=\"width:110px; height:25px; border:transparent; cursor:pointer;  background-image: url('images/$icono'); background-repeat:no-repeat;\"";
            $valor = "";
        }else{
            $estilo = "class = \"boton\"";
        }


        $salida = "<input type = \"submit\" name = \"$nombre\" id = \"$nombre\" value = \"$valor\" onsubmit = \"$evento\"  $tag $estilo />";
        return $salida;
    }
    
    function vinculo_boton($titulo,$href,$rel="",$destino="",$popup=0){
        
        $salida = "<a rel = \"$rel\" class = \"boton\" href = \"$href\" >$titulo</a>";//target = \"$destino\"
        return $salida;

        
    }
    
    function boton_opcion($nombre,$valor,$evento){
        $salida = "<input type = \"radio\" name = \"$nombre\" id = \"$nombre\" value = \"$valor\" onclick = \"$evento\" />";
        return $salida;
    }
    
   public static function campo_password($nombre,$tam, $tag=0,$pref=""){
        $salida = "<input type = \"password\" name = \"$pref$nombre\" id = \"$nombre\" size = \"$tam\" $tag   />";
        return $salida;
    }

    function grupo_opciones($nombre,$valores,$titulos,$predeterminado,$evento,$pref){
    $n = 0;
                $salida = " | ";
		foreach ($valores as $valor) {

		if ($predeterminado == $valor)
			$check = "CHECKED";
			else
			$check = "";

			$salida.= $titulos[$n]."<input onchange=\"$('$nombre').value='$valor'\" type = \"radio\" value = \"$valor\" id = \"Opt_$nombre\" name = \"Opt_$nombre\" $check  $evento> | ";
			$n++;
		}
                $salida.="<input type = \"hidden\" id = \"$nombre\" name = \"$pref$nombre\" value = \"$predeterminado\">";
		return $salida;
	}
        
    function grupo_check($nombre,$valores,$titulos,$predeterminado,$evento,$pref=NULL){
                $check = NULL;
                $n = 0;
                $salida = " | ";
		foreach ($valores as $valor) {
                if ($predeterminado){
                    $auxSeleccionado = explode("|", $predeterminado);
                    
                    if ( is_numeric(array_search($valor.":true",$auxSeleccionado)))
                        $check = "CHECKED";
			else
			$check = "";
                }
		
			
			$salida.= "<label for = \"$valor\">".$titulos[$n]."<input onclick = \"seleccionarCheck(this,'$nombre');\" class=\"opcion_$nombre\" type = \"checkbox\" value = \"$valor\" name = \"$valor\" $check  $evento> </label>| ";
			$n++;
		}
                $salida.="<input type = \"hidden\" value=\"$predeterminado\"  name=\"$pref$nombre\" id=\"$nombre\"  />";
		return $salida;
                
	}

    public static function campo_oculto($nombre,$valor,$pref=""){
	 $salida = "<input type = \"hidden\" name = \"$pref$nombre\" id = \"$nombre\" value = \"$valor\" />";
        return $salida;
    }
    
}
?>
