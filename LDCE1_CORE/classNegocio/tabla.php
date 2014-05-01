<?php
if (!isset($_SESSION)){ session_start(); }
include 'db.php';
class tabla{
		var $nombretabla;
		var $banderalocal;
		function __construct($nombretabla)
		{
			$this->nombretabla=$nombretabla;
			
		}//end function tabla

		//Funcion que se encarga de realizar insert a la tabla indicada
		//los datos se agregan en mayusculas
		function agregarDatos($campos,$db)
		{
			global $loginusuarioactual;
			if(!empty($campos))
			{
				$i=0;
				foreach($campos as $camp => $val)
				{
					if($i==0)
					{
						$fields.=$camp;
						$values.="'".htmlentities($val)."'";
					}
					else
					{
						$fields.=",".$camp;
						$values.=",'".htmlentities($val)."'";
					}
					$i++;
					next($campos);
				}
				$sqlAgregar="INSERT INTO ".$this->nombretabla." ( ".$fields.",fechacreacion,creadopor,fechaultmodificacion,modificadopor ) VALUES ( ".$values.",NOW(),'".$_SESSION['loginusuarioactual']."',NOW(),'".$_SESSION['loginusuarioactual']."' ) ";
				$db->query($sqlAgregar);
				return  $db->query($sqlAgregar);
				$sql='INSERT INTO logtransacciones (fechatransaccion,horatransaccion,nombretabla,loginusuario,accion,querysql,ip)
					  VALUES (CURDATE(),CURTIME(),"'.$this->nombretabla.'","'.$_SESSION['user'. __baseDatos].'","AGREGAR NUEVO DATO","'.$sqlAgregar.'","'.$_SERVER['REMOTE_ADDR'].'")';
				$db->query($sql);
				//if($this->banderalocal==1)
				//{
                                  //  echo "Paso...";
				//	$sql='INSERT INTO logquery (senteciaquery,estado,fechacreacion,creadopor)
				//		  VALUES ("'.$sqlAgregar.'","0",NOW(),"'.$_SESSION['loginusuarioactual'].'")';
				//	$db->query($sql);
				//}
			}
		}

		//Funcion que se encarga de realizar insert a la tabla indicada
		//los datos se agregan tal como los digito el usuario
		function agregarDatosMinus($campos,$db)
		{
                    $fields = "";
                    $values = "";
                    $accion = "";
			global $loginusuarioactual;
			if(!empty($campos))
			{
				$i=0;
				foreach($campos as $camp => $val)
				{
                                    if($val=="")
                                        $val=" ";
                                    
					if($i==0)
					{
						$fields.="`".$camp."`";
						$values.="'".$val."'";
					}
					else
					{
						$fields.=',`'.$camp."`";
						$values.=",'".$val."'";
					}
					$i++;
					next($campos);
				}
				 $sqlAgregar="INSERT INTO ".$this->nombretabla." ( ".$fields." ) VALUES ( ".$values.") ";
				date_default_timezone_set("America/Bogota");
				   $fecha = time (); 
				   
				$sql='INSERT INTO logtransacciones (fechatransaccion,horatransaccion,nombretabla,loginusuario,accion,querysql,ip)
					  VALUES (CURDATE(),"'.date ( "H:i" , $fecha ) .'","'.$this->nombretabla.'","'.$_SESSION['user'. __baseDatos].'","AGREGAR NUEVO DATO","'.$sqlAgregar.'","'.$_SERVER['REMOTE_ADDR'].'")';
				$db->query($sql);
				return  $db->query($sqlAgregar);
				//if($this->banderalocal==1)
				//{
                                  //  echo "Paso...";
				//	$sql='INSERT INTO logquery (senteciaquery,estado,fechacreacion,creadopor)
				//		  VALUES ("'.$sqlAgregar.'","0",NOW(),"'.$_SESSION['loginusuarioactual'].'")';
				//	$db->query($sql);
				//}
			}
		}

		//Funcion que se encarga de realizar update a la tabla indicada
		//Se le indica los campos y la condicion a utilizar
		//los datos se actualizan en mayusculas
		function actualizarDatos($campos,$condicion,$db)
		{
			global $loginusuarioactual;
                        $values = "";
			if(!empty($campos))
			{
				$i=0;
				foreach($campos as $camp => $val)
				{
					if($i==0)
					{
						$values.=$camp." = '".htmlentities(pasarMayusculas($val))."'";
					}
					else
					{
						$values.=",".$camp." = '".htmlentities(pasarMayusculas($val))."'";
					}
					$i++;
					next($campos);
				}
				$values.=',fechaultmodificacion=NOW(),modificadopor=\''.$_SESSION['loginusuarioactual'].'\' ';
				if(!empty($condicion))
				{
					$i=0;
					foreach($condicion as $camp => $val)
					{
						if($i==0)
						{
							$cond.=" WHERE ".$camp." = '".$val."'";
						}
						else
						{
							$cond.=" AND ".$camp." = '".$val."'";
						}
						$i++;
						next($condicion);
					}
				}
				$sqlActualizar="UPDATE ".$this->nombretabla." SET ".$values.$cond;
				$db->query($sqlActualizar);
					date_default_timezone_set("America/Bogota");
				   $fecha = time (); 
					$sql='INSERT INTO logtransacciones (fechatransaccion,horatransaccion,nombretabla,loginusuario,accion,querysql,ip)
					  VALUES (CURDATE(),"'.date ( "H:i" , $fecha ) .'","'.$this->nombretabla.'","'.$_SESSION['nombreUsuario'. __baseDatos].'","Actualizar Registro","'.$sqlActualizar.'","'.$_SERVER['REMOTE_ADDR'].'")';
				$db->query($sql);
			}
		}
		//Funcion que se encarga de realizar update a la tabla indicada
		//Se le indica los campos y la condicion a utilizar
		//los datos se actualizan tal como los digito el usuario
		function actualizarDatosMinus($campos,$condicion,$db)
		{
                    $values = "";
                    $cond = "";
			global $loginusuarioactual;
			if(!empty($campos))
			{
				$i=0;
				foreach($campos as $camp => $val)
				{
                                     if($val=="")
                                        $val=" ";
                                     
					if($i==0)
					{
						$values.="`".$camp."`"." = '".$val."'";
					}
					else
					{
						$values.=","."`".$camp."`"." = '".$val."'";
					}
					$i++;
					next($campos);
				}
				if(!empty($condicion))
				{
					$i=0;
					foreach($condicion as $camp => $val)
					{
						if($i==0)
						{
							$cond.=' WHERE `'.$camp."`"." = '".$val."'";
						}
						else
						{
							$cond.=" AND "."`".$camp."`"." = '".$val."'";
						}
						$i++;
						next($condicion);
					}
				}
				$sqlActualizar="UPDATE ".$this->nombretabla." SET ".$values.$cond;
					date_default_timezone_set("America/Bogota");
				   $fecha = time (); 
				 $sql='INSERT INTO logtransacciones (fechatransaccion,horatransaccion,nombretabla,loginusuario,accion,querysql,ip)
					  VALUES (CURDATE(),"'.date ( "H:i" , $fecha ) .'","'.$this->nombretabla.'","'.$_SESSION['user'. __baseDatos].'","Actualizar Registro","'.$sqlActualizar.'","'.$_SERVER['REMOTE_ADDR'].'")';
				$db->query($sql);
				return $db->query($sqlActualizar);
			}
		}
		
		//Funcion que se encarga de realizar update a la tabla indicada
		//Se le indica los campos y la condicion a utilizar
		function eliminarDatos($condicion,$db)
		{
			$cond=NULL;
			if(!empty($condicion))
			{
				$i=0;
				foreach($condicion as $camp => $val)
				{
					if($i==0)
					{
						$cond.=" WHERE `".$camp."` = '".$val."'";
					}
					else
					{
						$cond.=" AND `".$camp."` = '".$val."'";
					}
					$i++;
					next($condicion);
				}
				$sqlEliminar="DELETE FROM ".$this->nombretabla.$cond;
					date_default_timezone_set("America/Bogota");
				   $fecha = time (); 
				$sql='INSERT INTO logtransacciones (fechatransaccion,horatransaccion,nombretabla,loginusuario,accion,querysql,ip)
					  VALUES (CURDATE(),"'.date ( "H:i" , $fecha ) .'","'.$this->nombretabla.'","'.$_SESSION['user'. __baseDatos].'","ELIMINAR REGISTRO","'.$sqlEliminar.'","'.$_SERVER['REMOTE_ADDR'].'")';
				$db->query($sql);
				return $db->query($sqlEliminar);
				
			}
		}

		//Funcion que se encarga de realizar update a la tabla indicada
		//Se le indica los campos y la condicion a utilizar
                
		function consultarDatos($campos,$condicion="",$orden="",$RegistroInicia="",$RegistroCantidad="",$CondicionAdicional="",$db)
		{
                    $cond = NULL;
                    $Limit = NULL;
                    $consulta = NULL;
                    $row = NULL;
			if(!empty($condicion))
			{
				$i=0;
				foreach($condicion as $camp => $val)
				{
                                   // if($val=="")
                                      //  $val="0";
                                        
					if($i==0)
					{
						$cond.=" WHERE `".$camp."` = '".$val."'";
					}
					else
					{
						$cond.=" AND `".$camp."` = '".$val."'";
					}
					$i++;
					next($condicion);
				}
			}
			if($orden!="")
			{
				$orden=" ORDER BY ".$orden;
			}
			$RegistroInicia=sprintf('%s',$RegistroInicia);
			$RegistroCantidad=sprintf('%s',$RegistroCantidad);
			if($RegistroInicia!="" && $RegistroCantidad!="")
			{
				//$Limit=" LIMIT ".$RegistroInicia.",".$RegistroCantidad;
                           $Limit=" LIMIT ".$RegistroCantidad." offset ".$RegistroInicia;
			}
			$sqlConsultar="SELECT ".$campos." FROM ".$this->nombretabla.$cond.' '.$CondicionAdicional.$orden.$Limit;
                        
			$queryresult = $db->query($sqlConsultar,1);
                        //echo $sqlConsultar;
			while($row=$db->fetchArray($queryresult))
			{
                            //echo "Paso-";
				$consulta[]=$row;
			}
			$db->freeResult($queryresult);
			if ($this->nombretabla!="permiso" && isset($_SESSION['user'. __baseDatos])){
				date_default_timezone_set("America/Bogota");
				   $fecha = time (); 
				$sql='INSERT INTO logtransacciones (fechatransaccion,horatransaccion,nombretabla,loginusuario,accion,querysql,ip)
					  VALUES (CURDATE(),"'.date ( "H:i" , $fecha ) .'","'.$this->nombretabla.'","'.$_SESSION['user'. __baseDatos].'","CONSULTA DATOS","'.$sqlConsultar.'","'.$_SERVER['REMOTE_ADDR'].'")';
				$db->query($sql);
			}
			return $consulta;
		}
            function consultarDatosSimil($campos,$condicion="",$orden="",$RegistroInicia="",$RegistroCantidad="",$CondicionAdicional="",$db,$reportaTotal=0)
                            {
                                $cond = NULL;
                                $Limit = NULL;
                                $consulta = NULL;
                                $row = NULL;
                                    if(!empty($condicion))
                                    {
                                            $i=0;
                                            foreach($condicion as $camp => $val)
                                            {
                                                    if($i==0)
                                                    {
                                                            $cond.=" WHERE `".$camp."` like '%".$val."%'";
                                                    }
                                                    else
                                                    {
                                                            $cond.=" AND `".$camp."` like '%".$val."%'";
                                                    }
                                                    $i++;
                                                    next($condicion);
                                            }
                                    }
                                    if($orden!="")
                                    {
                                            $orden=" ORDER BY ".$orden;
                                    }
                                    $RegistroInicia=sprintf('%s',$RegistroInicia);
                                    $RegistroCantidad=sprintf('%s',$RegistroCantidad);
                                    if($RegistroInicia!="" && $RegistroCantidad!="")
                                    {
                                            //$Limit=" LIMIT ".$RegistroInicia.",".$RegistroCantidad;
                                          $Limit=" LIMIT ".$RegistroCantidad." offset ".$RegistroInicia;
                                    }
                                    
                                 $sqlConsultar="SELECT ".$campos." FROM ".$this->nombretabla.$cond.' '.$CondicionAdicional.$orden.$Limit;
                                   
                                   
                                    $queryresult = $db->query($sqlConsultar,1);
                                    
                                    $sqlTodos="SELECT ".$campos." FROM ".$this->nombretabla.$cond.' '.$CondicionAdicional.$orden;
                                    $queryresultTodos = $db->query($sqlTodos,1);
                                    $cantidad = $db->numRows($queryresultTodos);
                                    
                                    //echo $sqlConsultar;
                                    while($row=$db->fetchArray($queryresult))
                                    {
                                        //echo "Paso-";
                                            $consulta[]=$row;
                                    }
                                    $db->freeResult($queryresult);
                                    if ($reportaTotal==0)
                                        return $consulta;
                                    else
                                        return array($consulta,$cantidad);
                }
		//Funcion que se encarga de realizar update a la tabla indicada
		//Se le indica los campos y la condicion a utilizar
		function cantidadDatos($campos,$condicion,$db)
		{
                    $cond= NULL;
			if(!empty($condicion))
			{
				$i=0;
				foreach($condicion as $camp => $val)
				{
					if($i==0)
					{
						$cond.=" WHERE ".$camp." = '".$val."'";
					}
					else
					{
						$cond.=" AND ".$camp." = '".$val."'";
					}
					$i++;
					next($condicion);
				}
			}
			$sqlConsultar="SELECT ".$campos." FROM ".$this->nombretabla.$cond;
			$queryresult = $db->query($sqlConsultar);
			return $db->numRows($queryresult);
		}

		//Funcion que consulta el maximo registro de un campo determinado
		function MaximoDato($campo,$condicion,$db)
		{
		$cond = NULL;
			if(!empty($condicion))
			{
				$i=0;
				foreach($condicion as $camp => $val)
				{
					if($i==0)
					{
						$cond.=" WHERE ".$camp." = '".$val."'";
					}
					else
					{
						$cond.=" AND ".$camp." = '".$val."'";
					}
					$i++;
					next($condicion);
				}
			}
			$sqlConsultar="SELECT MAX(".$campo.") AS ".$campo." FROM ".$this->nombretabla.$cond;
			$queryresult = $db->query($sqlConsultar);
			$row=$db->fetchArray($queryresult);
			$db->freeResult($queryresult);
			return $row[$campo];
		}
}//end clase Tabla
?>