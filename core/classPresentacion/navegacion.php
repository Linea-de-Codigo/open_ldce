<?php
//dimar B

class elementos_interfase{
    function filas($tabla,$filtro,$db){

        if($db==""){$db=0;}
        $base_datos_interfase = new gestiona_base();
       $base_datos_interfase->base_trabajo = $db;
       $registros = $base_datos_interfase->filas("$tabla", "$filtro");
       return $registros;
    }

    function interfase($modulo,$direccion){
        global $tags, $variables;
        
        if (!$direccion || $direccion == 0)  
            $direccion = $_SESSION['subsistema'];
        
       $elemento_menu = $this->filas("menu", " where id = ".$direccion,"1");
      
       if (!$elemento_menu)
           Header("Location: $variables->C_errores"."no_existe_dir.php"); ;

        if ($modulo == "head"){
           $salida.= $this->head($direccion,$elemento_menu);
        }
        if ($modulo == "body"){
           $salida.= $this->body($direccion,$elemento_menu);
        }
        if ($modulo == "bottom"){
           $salida.= $this->bottom($direccion, $elemento_menu);
        }
        
    return $salida;

    }




    function favoritos($direccion){
        global $controles_usuario;
        $favoritos = $this->filas("personal", " where tipo = 'favoritos' and id_usuario = ".$_SESSION['nombre_usuario'], 0);
         $buscar_menu = $controles_usuario->lista_ajax("menu","descripcion","id","busqueda_menu",25,"","","class = \"buscar_control\" onchange = \"selecciona_menu(document.getElementById('busqueda_menu').value)\"",1,"Buscar");
        $salida.=" $buscar_menu<div style = \" height: 200px; overflow-y: scroll; \"><ul style = \"list-style: none;padding: 0; margin: 0;\">";
        $favoritos_[] = "";
        if ($favoritos)
            do{
                $menu = $this->filas("menu"," where id = ".$favoritos[0]['valor'],1);
                $salida.= "<li style = \"margin: 0 0.15em;\"><a style = \"display: block; text-align: center;height: 2em;line-height: 2em;background-color:#cccccc; margin-top:5px; padding:0px 5px;\" href = \"?dir=".$menu[0]['id'].$menu[0]['get']."\">".$menu[0]['descripcion']."</a></li>" ;
                $favoritos_[] = $favoritos[0]['valor'];
            }while ($favoritos[0]= mysql_fetch_assoc($favoritos[1]));
           
        $salida.="</ul></div>";

        if (in_array($direccion,$favoritos_)){
            $salida.="<a href = \"?dir=197&quita=$direccion\">Quitar de Favoritos</a> ";
        }else{
            $salida.="<a href = \"?dir=197&agrega=$direccion\">Agregar a Favoritos</a> ";
        }

        return $salida."<hr />";
    }
    
    function caja_ayuda($id){
        $salida.= "
            <script type = \"\">
            function crea_div(id,X,Y,tipo){
            try{
                var b = document.getElementById('div_ayuda');
                document.body.removeChild(b);
            } catch(err) {
                n=1;
            }
            var p = document.createElement(\"div\");
                p.id = 'div_ayuda';
                p.style.top = Y-120;
                p.style.zIndex = 102;
                p.style.left = X + 5;
                 document.body.appendChild(p);
                 mensaje_ayuda(id,tipo);
            }
            function quita_div(){
                try{
                    var b = document.getElementById('div_ayuda');
                    document.body.removeChild(b);
                } catch(err) {
                    n=1;
                }
            }
            </script>";
        $salida.= "<img src = \"images/Info.png\" style = \"cursor:pointer;\" onclick = \"crea_div(".$id[0]['id'].",event.clientX,event.clientY,1)\">";

        return $salida;
    }


    function aca_estas($id){
    if ($id){
      $FilasMenu = $this->filas("menu", " where id = $id","1");
      $cont = 0;
          do{
                $identificador[$cont] = $FilasMenu[0]['id'];
                $nombre[$cont] = $FilasMenu[0]['descripcion'];
                $archivo[$cont] = $FilasMenu[0]['url'];
                $Vget[$cont] = $FilasMenu[0]['get'];
                $imagenes[$cont] = $FilasMenu[0]['css']."/";


                    if (!$FilasMenu[0]['css'])
                        $imagenes[$cont] = "contable_financiero/";

                   // echo $imagenes[$cont -1];
                 $FilasMenu = $this->filas("menu", " where id = ".$FilasMenu[0]['depende'],"1");
                $cont++;
                //echo $_SESSION['subsistema']." / ".$FilasMenu[0]['id'];
                
                
          }while ($FilasMenu[0]);
          $salida.= "<div id = \"div_aca\" class = \"aca_estas\" style = \"margin-left:15px;z-index:50;height:30px;overflow:auto;padding-top:3px;\" > ";
          $inicio = 0;
          for ($n=$cont;$n>=0;$n--){
              if ($_SESSION['subsistema'] == $identificador[$n]){
                  $inicio = 1;
              }
              if ($inicio == 1){
                if ($archivo[$n]){
                    if ($n==0){
                        $salida.= $nombre[$n];
                    }else{
                    $salida.= "<a href = \"?dir=".$identificador[$n].$Vget[$n]."\" style=\"padding-bottom:7px;\">".$nombre[$n]."</a>".$tags->imagen($imagenes[$n-1]."vineta.jpg");
                    }
                }else{
                    if ($n==0){
                        $salida.= $nombre[$n];
                    }else{
                        $salida.= "<a href = \"?dir=".$identificador[$n].$Vget[$n]."\" style=\"padding-bottom:7px;\">".$nombre[$n]."</a>".$tags->imagen($imagenes[$n-1]."vineta.jpg");
                    }
                }
              }
          }

          $salida.= "</div>";
          
          return $salida;

          }
    }
    function contenidos($id){
   
    }

    function  armar_mapa($id){
            global $variables,$saludo;
       
        $hijos = $this->filas("menu", "where depende = $id",1);
        $padre = $this->filas("menu", "where id = $id",1);
     
        $padre_id = $padre[0]['depende'];
        if(!$hijos)
           return "<div style = \"height:400px;\"><h1>No hay elementos en este men&uacute;</h1></div>";

           $ruta = "images/mapas/";
           $salida.="<div id = \"contenedor_menus\" style = \"height:636px;position:relative;background-repeat:no-repeat; background-color: transparent; background-image: url(".$ruta."mapa_$id.jpg)\">\n";//
           if ($_SESSION['subsistema'] != $id){
             $estilo = 'style = "cursor:pointer;position:absolute;left:10px;top:10px;width:80px;height:30px;"';
			$salida.="<div style = \"position:relative;\"><a href = \"?dir=$padre_id$get\"><div id = \"emeneto_$id\" $estilo  >".$hijos[0]['descripcion']."</div></a></div>\n";
           }

           if ($id == 1){
               $ancho = 130;
               $alto = 160;
           }else{
                $ancho = 160;
               $alto = 180;
           }
            do{

            if ($this->define_acceso($hijos[0]['id'],$_SESSION['nivel_usuario'])){

	    if ($hijos[0]['oculto']==0){
			$id_menu = $hijos[0]['id'];
			$get = $hijos[0]['get'];
			$estilo = 'style = "cursor:pointer;position:absolute;left:'.$hijos[0]['x'].'px;top:'.$hijos[0]['y'].'px;width:'.$ancho.'px;height:'.$alto.'px;"';
			$salida.="<div style = \" position:relative;\"><a href = \"?dir=$id_menu$get\"><div onmouseout = \"quita_div()\" onmousemove = \"document.getElementById('div_ayuda').style.top=event.clientY-120;document.getElementById('div_ayuda').style.left=event.clientX+5;\" onmouseover = \"crea_div(".$hijos[0]['id'].",event.clientX,event.clientY,1)\" id = \"emeneto_$id_menu\" $estilo  >".$hijos[0]['descripcion']."</div></a></div>\n";
		}
            }else{
                        $id_menu = $hijos[0]['id'];
			$get = $hijos[0]['get'];
                        $estilo = 'style = "cursor:pointer;position:absolute;left:'.$hijos[0]['x'].'px;top:'.$hijos[0]['y'].'px;width:'.$ancho.'px;height:'.$alto.'px;"';
			$salida.="<div style = \"position:relative;\"><div onmouseout = \"quita_div()\" onmousemove = \"document.getElementById('div_ayuda').style.top=event.clientY-120;document.getElementById('div_ayuda').style.left=event.clientX+5;\" onmouseover = \"crea_div(".$hijos[0]['id'].",event.clientX,event.clientY,1)\" id = \"emeneto_$id_menu\" $estilo  ><img src = \"images/Warning.png\" alt = \"No tiene acceso\"></div></div>\n";
            }
            }while ($hijos[0]= mysql_fetch_assoc($hijos[1]));
            $salida.="</div>";
            return $salida;
    }
    function define_acceso($id,$nivel){

        if ($nivel==0)
            return  true;

        $permiso  = $this->filas("permisos", "where item_menu = $id and nivel_usua = $nivel",0);
        if ($permiso)
            return true;
        else
            return false;
    }
    function incluir_modulo ($archivo,$compatibilidad){
    
    }
    



function arma_menu($tabla,$pos){
	global $vars;
	$filas_menu =  $this->filas($tabla,"where depende  = $pos","1");
	$pos = 0;
	do{
		if ($filas_menu[0]['url']){
		$link = "?dir=".$filas_menu[0]['id']."&".$filas_menu[0]['get'];
		$sub ="";
		$evento = "";
		}else{
		$link = "#";
		$evento = "
		onmouseover = \"document.getElementById('subm".$filas_menu[0]['id']."').style.display=''\"
		onmouseout = \"document.getElementById('subm".$filas_menu[0]['id']."').style.display='none'\"";
		$sub = $this->arma_submenu($tabla,$filas_menu[0]['id']);
		}
		//$evento_descripcion = "onmouseover = \"buscar('".$filas_menu[0]['id']."','descripcion', '$tabla','".$vars['div_descripcion']."','',190,'info.png'); \"
		//onmouseout = \"document.getElementById('describir').innerHTML = ''\"";
		$esc.= "<div class=\"itemmenu\" style = \"left:".$pos."px;\" $evento ><a href = \"$link\">
		<div class = \"itemmenus\" style = \"height:28px;background-image: url(imgs/pestana.png);\" ".$evento_descripcion.">".$filas_menu[0]['descripcion']."</div></a>".$sub."</div>\n";
	$pos += 115;
	}while($filas_menu[0] = mysql_fetch_assoc($filas_menu[1]));
	$esc.= "<a href = \"?\"><div class=\"itemmenu\" style = \"left:".$pos."px\"><div class = \"itemmenus\" style = \"height:28px;background-image: url(imgs/pestana.png);\" >Men&uacute;</div></div></a>";
	return $esc;
	}

	function arma_submenu($tabla,$id){
		$filas_submenu =  $this->filas($tabla,"where  depende  = $id","1");
		$res = "<div id = \"subm".$id."\" class = \"itemmenu\" style = \"display:none;top:30px;z-index:50\">";
		if (!$filas_submenu[0])
			return "";

		do{
			//$evento_descripcion = "onmouseover = \"buscar('".$filas_submenu[0]['id']."','descripcion', '$tabla','describir','',190,'info.png'); \"
			//onmouseout = \"document.getElementById('".$vars['div_descripcion']."').innerHTML = ''\"";


			$link = "index.php?dir=".$filas_submenu[0]['id']."&".$filas_submenu[0]['get'];

			$res.="<a href = \"$link\"><div style = \"z-index:50;height:28px;background-image: url(imgs/pestana.png);\" class = \"itemmenus\" ".$evento_descripcion.">".$filas_submenu[0]['descripcion']."</div></a>";
		}while($filas_submenu[0] = mysql_fetch_assoc($filas_submenu[1]));
		$res.="</div>";
		return $res;
	}

	
}
?>
