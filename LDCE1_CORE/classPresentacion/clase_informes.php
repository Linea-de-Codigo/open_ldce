<?php
//Diseñada Por Dimar Borda
$globales = new cargar_variables();
class informes_nodriza{
   
        function entrada_informe($id_informe,$filtro,$filtro1){

                $salida = NULL;
		$fila_informe = $this-> busca_informe($id_informe);
		if ($fila_informe){
			$salida.= $this->crea_informe($fila_informe,$filtro,$filtro1);
		}else{
			$salida.="No hay datos en el presente Informe";
		}
		$salida.= "<div class = \"lines\" ><strong>Fecha:</strong>".date("d-m-Y")."<strong> </div>";

                return $salida;
	}

        function busca_informe($id_informe){
            $globales = new cargar_variables();
            
            $conDatosEdita = new db($globales, __baseDatos,"editar");
            $conexion = $conDatosEdita->connect(true);
            $conexion = $conDatosEdita->db(true);
            $conexion = $conDatosEdita->setEncoding();

		$sql_reg="select * from informes where ID_INFORME=".$id_informe;
             	$rs_reg = $conDatosEdita->query($sql_reg, 1);
		$row_reg = mysql_fetch_assoc($rs_reg);
		if ($row_reg){
			return array($row_reg,$rs_reg);
		}else{
			return 0;
		}
	}

 function crea_informe($filas,$filtro,$filtro1){

		$anteriores = explode(",",$filas[0]['saldo_anterior']);
                $campos="";
                $sql_agrupamiento = "";
                $des_informe=$filas[0]['DES_INFORME'];
                $des_agrupamiento=$filas[0]['DES_AGRUPAMIENTO'];
                $columnas = explode(",", $filas[0]['COLUMNAS']);
                $titulos = explode(",", $filas[0]['DES_COLUMNAS']);
                $totales=explode(",", $filas[0]['TOTAL']);
                $alineacion=explode(",", $filas[0]['alineacion']);
                $agrupamientos=explode(",",$filas[0]['AGRUPAMIENTO']);
                
                if (isset($filas[0]['estilosColumnas']))
					if ($filas[0]['estilosColumnas']!="")
						 $estilosColumnas = explode(",", $filas[0]['estilosColumnas']);
						 
				if (isset($filas[0]['agruparColumnas']))
					if ($filas[0]['agruparColumnas']!="")
						 $agruparColumnas = explode(",", $filas[0]['agruparColumnas']);
						
		$agrupamiento = $agrupamientos[0];
		$agrupamiento1 = $agrupamientos[1];
               $cam_total=$filas[0]['TOTAL'];              
	
		$primero = 0;
		
		foreach($totales as $total){
			if ($total){
				if ($primero == 0)
					$campos.= "$agrupamiento, $agrupamiento1, sum(".$total.") as total_".$total;
					else
					$campos.= ", sum(".$total.") as total_".$total;
					
			$primero = 1;
			}
		}
		
			if (!$campos)
			$campos = "*";
		
		 $sql_agrupamiento.= "select $campos FROM (".$filas[0]['CONSULTA'].$filtro.") as tabla1 group by $agrupamiento";
		
		//echo $sql_agrupamiento."</br>";
			$primero = 0;
		
		foreach($anteriores as $anterior){
			if ($anterior){
				if ($primero == 0)
					$campos.= "$agrupamiento, sum(".$anterior.") as total_".$anterior;
					else
					$campos.= ", sum(".$anterior.") as total_".$anterior;
					
			$primero = 1;
			}
		}
		
		if (!$campos)
			$campos = "*";


		$datos_agrupamiento =  $this->conjunto_datos($sql_agrupamiento);
		
		$salida = "<div>";
		$datoTotal= array("debe"=>0,"haber"=>0,"total"=>0);
		if ($datos_agrupamiento[0])
		do{
			if ($des_agrupamiento!="0"){
					$des_agrupamiento_ ="$des_agrupamiento :";
			}else{
				$des_agrupamiento_ = "";
			}
			if ($datos_agrupamiento[0][$agrupamiento1] != "")
				$salida.= "<hr /><div style = \"font-size:16px;font-weight:bold;\">$des_agrupamiento_ ".$datos_agrupamiento[0][$agrupamiento].", ".$datos_agrupamiento[0][$agrupamiento1]."</div>";	
				else
				$salida.= "<hr /><div style = \"font-size:16px;font-weight:bold;\">$des_agrupamiento_ ".$datos_agrupamiento[0][$agrupamiento]."</div>";	
			
			$salida.="<table width = \"100%\"><thead><tr>";
			$sumCols = 0;
			$conteoCols = 0;
			
			if (isset($agruparColumnas))
				if (is_array($agruparColumnas)){
					foreach($titulos as $llave => $titulo){
						
						$agruparCol = explode("|",$agruparColumnas[$conteoCols]);
						if ($agruparCol[1]-1==$llave-$sumCols){
								$salida.="<th colspan = '$agruparCol[1]'>".$agruparCol[0]."</th>";
								$sumCols+=$agruparCol[1];
								$conteoCols++;
						}
						
					}
					$salida.="</tr><tr>";
				}
			
			foreach($titulos as $llave => $titulo){
				
				if (isset($estilosColumnas[$llave]))
					$estiloCol = $estilosColumnas[$llave];
				else 
					$estiloCol = "";
				
				
				
				$salida.="<th style = \"$estiloCol\" > $titulo</th>";
			}
			$salida.="</thead></tr>";
			
			$sql_anteriores= "select $campos FROM (".$filas[0]['CONSULTA'].$filtro1.") as tabla1 where $agrupamiento = '".$datos_agrupamiento[0][$agrupamiento]."'  group by $agrupamiento";
			//echo $sql_anteriores;
			$datos_anteriores =  $this->conjunto_datos($sql_anteriores);
				
			$salida.= "<tr style =\"border-bottom:solid 1px;\">";
			foreach($anteriores as $anterior){
				if ($anterior)
					$dato = number_format($datos_anteriores[0]['total_'.$anterior], 0, ",", ".");
					else
					$dato = "";
				$salida.="<td style = \"text-align:right;\" >".$dato."</td>";
			}
			$salida.="</tr>";
			
			$sql_esta_seccion = "select * from (".$filas[0]['CONSULTA'].$filtro.") as tabla where $agrupamiento = '".$datos_agrupamiento[0][$agrupamiento]."'";
			$datos_esta_seccion = $this->conjunto_datos($sql_esta_seccion);
			
			
			if($datos_esta_seccion[0])
			do{
			
			$salida.= "<tr>";
			$col_ = 0;
			foreach($columnas as $columna){
				switch ($alineacion[$col_]){
					case 1:
					$estilo = "text-align:left";
					break;
					case 2:
					$estilo = "text-align:center";
					break;
					case 3:
					$estilo = "text-align:right";
					break;
				}
				
				
					
				$salida.="<td style = \"$estilo\" class = \"listas\">".$datos_esta_seccion[0][$columna]."</td>";
				
				$col_++;
			}
			$salida.="</tr>";
			
			}while($datos_esta_seccion[0] = mysql_fetch_assoc($datos_esta_seccion[1]));
			
			
			$salida.= "<tr class = \"titulos\">";
			foreach($totales as $total){
				if ($total){
					$dato = number_format($datos_agrupamiento[0]['total_'.$total], 0, ",", ".");
					if (isset($datoTotal[$total])) $datoTotal[$total]+=$datos_agrupamiento[0]['total_'.$total];
						else $datoTotal[$total]=$datos_agrupamiento[0]['total_'.$total];
					}else{
					$dato = "";
					}
				$salida.="<td style = \"text-align:right;\" >".$dato."</td>";
				
			}
			$salida.="</tr>";
			
			//Nuevo saldo
				
			$salida.= "<tr style =\"border-bottom:solid 1px;\">";
			foreach($anteriores as $anterior){
				if ($anterior)
					$dato = number_format($datos_anteriores[0]['total_'.$anterior] + $datos_agrupamiento[0]['total_'.$anterior], 0, ",", ".")  ;
					else
					$dato = "";
				$salida.="<td style = \"text-align:right;\" >".$dato."</td>";
			}
			$salida.="</tr>";	
			
			
			$salida.="</table>";
			
		
		}while($datos_agrupamiento[0] = mysql_fetch_assoc($datos_agrupamiento[1]));
		$salida.= "<div style = \"font-size:16px;font-weight:bold;\" >Totales del Informe</div><table width = \"100%\"><tr>";
			foreach($titulos as $llave => $titulo){
				
			
			
				$salida.="<td style = \"font-size:16px;font-weight:bold;text-align:right;\"> $titulo</td>";
			}
			$salida.="</tr>";
			
			$salida.= "<tr style = \"font-size:16px;font-weight:bold;\">";
			foreach($totales as $total){
				if ($total){
						$dato = number_format($datoTotal[$total], 0, ",", ".");
					//$datoTotal[$total]+=$datos_agrupamiento[0]['total_'.$total];
					}else{
						$dato = "";
					}
				$salida.="<td style = \"text-align:right;\" >".$dato."</td>";
				
			}
			$salida.="</tr></table>";
		
		$salida.= "</div>
		<div align = \"center\">Generado por LDCE</div>";
		return $salida;

	}

        function conjunto_datos($sql_reg){
          $globales = new cargar_variables();
            
            $conDatosEdita = new db($globales, __baseDatos,"editar");
            $conexion = $conDatosEdita->connect(true);
            $conexion = $conDatosEdita->db(true);
            $conexion = $conDatosEdita->setEncoding();

		
             	
                
		//$sql_reg.=$filtro;

		$rs_reg = $conDatosEdita->query($sql_reg, 1);
		$row_reg = mysql_fetch_assoc($rs_reg);
		if ($row_reg){
			return array($row_reg,$rs_reg);
		}else{
			return 0;
		}
	}
}


?>
