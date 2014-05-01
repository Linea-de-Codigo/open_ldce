<?php 
class appError
{
 	var $numeroerror;
	var $descripcionerror;
        var $error_motor;
        var $control;
	
	function __construct()
	{
          //  $this->control = new controles();
	}	
	function mostrarError()
	{
            if ($this->numeroerror != 0){
		switch($this->numeroerror)
		{
				case 1030:
				$this->descripcionerror="No existe espacio suficiente en disco para el almacenamiento.";
				break;
				case 1046:
				$this->descripcionerror="La base de datos no ha sido seleccionada.";
				break;
				case 1049:
				$this->descripcionerror="La base de datos no existe.";
				break;
				case 1062:
				$this->descripcionerror="El registro que intenta almacenar ya se encuentra registrado.";
				break;
				case 1052:
				$this->descripcionerror="Ambig�edad en Columna en la Condici&oacute;n.";
				break;
				case 1054:
				$this->descripcionerror="Uno de los campos que intenta consultar o registrar no existe.";
				break;
				case 1064:
				$this->descripcionerror="Error de sintaxis en la sentencia.";
				break;
				case 1065:
				$this->descripcionerror="La consulta esta vacia.";
				break;
				case 1136:
				$this->descripcionerror="El n�mero de columnas no corresponde con la cantidad que se tratan de registrar.";
				break;
				case 1205:
				$this->descripcionerror="Tiempo de espera agotado para la operaci�n.";
				break;
				case 1146:
				$this->descripcionerror="No se encuentra la tabla.";
				break;
				case 1451:
				$this->descripcionerror="No es posible actualizar o eliminar el regitro debido a que existe informacion dependiente de este.";
				break;
				case 1452:
				$this->descripcionerror="No es posible agregar o actualizar el regitro debido a que existe informacion dependiente de este.";
				break;
				default:
				$this->descripcionerror="Se presento un error desconocido en la operaci&oacute;n.";
		}
                }
		if($this->descripcionerror!="")
		{
			return array("Erorr",0) ;
		}
	}
        function administra_log(){
            
        }
}//end appError


class db{

		//we may want to do more than connect via mysql;
		var $type="mysql";
		// mysql vars;
		public $db_link;
		var $hostnameremoto="localhost";
		var $dbuserremoto="";
		var $dbpassremoto="";
		var $hostnamelocal="";
		var $dbuserlocal="";
		var $dbpasslocal="";
		var $schema="";
		var $pconnect=true;
		
		var $showError=false;
		var $logError=true;
		var $stopOnError=true;
		var $errorFormat="xhtml";
		
		var $error = NULL;
                function  __construct($informacion,$shema){
                    $this->dbuserremoto = $informacion->Usuario;
                    $this->dbpassremoto = $informacion->Clave;
                    $this->schema = $shema;
                }
		function db($connect = true){
			$_SESSION['hostnameremoto']=$this->hostnameremoto;				
			$shema = NULL;
			switch($this->type){
				default:
				case "mysql":
                                        if(defined("MYSQL_SERVER"))
						$this->hostname = MYSQL_SERVER;
					if($hostname!=NULL)
						$this->hostname = $hostname;
					
					if(defined("MYSQL_DATABASE"))
						$this->schema = MYSQL_DATABASE;
					if($schema!=NULL)
						$this->schema = $schema;
		
					if(defined("MYSQL_USER"))
						$this->dbuser = MYSQL_USER;
					if($schema!=NULL)
						$this->dbuser = $user;
		
					if(defined("MYSQL_USERPASS"))
						$this->dbpass = MYSQL_USERPASS;
					if($schema!=NULL)
						$this->dbpass = $pass;
		
					if(defined("MYSQL_PCONNECT"))
						$this->pconnect = MYSQL_PCONNECT;
					if($pconnect!=NULL)
						$this->pconnect = $pconnect;
				break;				
			}
			if($connect){
				if($this->connect()){
					if($this->selectSchema())
						return $this->db_link;
					else
						return false;				
				} else
					return false;
			} else return true;
		}//end function


		function connect(){
			// This functions connects to the database.  It uses pconnect if the variable is set;
			$this->db_link = mysql_connect($this->hostnameremoto,$this->dbuserremoto,$this->dbpassremoto);
			if(!($this->db_link))
			{
				$_SESSION['hostnameactual']=$this->hostnamelocal;
				$this->db_link = mysql_connect($this->hostnamelocal,$this->dbuserlocal,$this->dbpasslocal);
			}
			else
			{
				//echo $_SESSION['hostnameactual']."!=".$_SESSION['hostnameremoto'];
				if($_SESSION['hostnameactual']!=$_SESSION['hostnameremoto'])
				{
					sincronizarServidor($this->hostnamelocal,$this->dbuserlocal,$this->dbpasslocal,$this->hostnameremoto,$this->dbuserremoto,$this->dbpassremoto);
					/*echo '
					<script language="javascript">
					alert("SINCRONIZACION COMPLETA");
					</script>';*/
				}
				$_SESSION['hostnameactual']=$this->hostnameremoto;
			}
			if(!$this->db_link){
                        
				$error = new appError(mysql_errno($this->db_link),mysql_error($this->db_link));
				$error->mostrarError();
				return false;
			} else
                            
				return $this->db_link;
		}

		
		function selectSchema($schema=NULL){
			if($schema!=NULL)
				$this->schema=$schema;
			//echo "sadasdsad";	
			if(! @ mysql_select_db($this->schema,$this->db_link)){
				$error = new appError(mysql_errno($this->db_link));
				$error->mostrarError();
				return false;
			} else
				return true;							
		}


		function query($sqlstatement){
                    //$control = new controles();
			switch($this->type){
				case "mysql":
					if(!isset($this->db_link)) 
						if(!$this->dataB()) die($this->error);
					$queryresult=  @ mysql_query($sqlstatement,$this->db_link);
                                        //echo mysql_errno($this->db_link );
					if(mysql_errno($this->db_link) <> 0){
						$errors = new appError();
						$errors->numeroerror = mysql_errno($this->db_link);
                                                echo mysql_error($this->db_link);
						//echo mysql_error($this->db_link);
						return $errors->mostrarError();
					}else{
                                            return 0;//array($control->div_msj("Guardado Correctamente"),1) ;
                                        }
                                        //echo "Paso";
				break;
			}//end case
			
		}//end function


		function setEncoding($encoding = "utf8"){

			switch($this->type){
				case "mysql":			
					@ mysql_query("SET NAMES ".$encoding, $this->db_link);
					break;
					
			}//endswitch

		}//end method


		function numRows($queryresult){
			switch($this->type){
				case "mysql":
					$numrows=@ mysql_num_rows($queryresult);
					if(!is_numeric($numrows)){
						$error = new appError(mysql_errno($this->db_link));
						$error->mostrarError();
						return false;
					}
				break;
			}//end case
			$this->error=NULL;
			return $numrows;
		}//end function

		function fetchArray($queryresult){
			//Fetches associative array of current row
                     
			switch($this->type){
                           
				case "mysql":
					$row=@ mysql_fetch_assoc($queryresult);
				break;
			}//end case
			return $row;
		}//end function

		function fetchRow($queryresult){
			//Fetches associative array of current row
			switch($this->type){
				case "mysql":
					$row=@ mysql_fetch_row($queryresult);
				break;
			}//end case
			return $row;
		}//end function


		function fetchobject($queryresult){
			//Fetches associative array of current row
			switch($this->type){
				case "mysql":
					$row=@ mysql_fetch_object($queryresult);
				break;
			}//end case
			return $row;
		}//end function


		function seek($queryresult,$rownum){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_data_seek($queryresult,$rownum);
				break;
			}//end case
			return $thereturn;
		}//end function


		function numFields($queryresult){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_num_fields($queryresult);
				break;
			}//end case
			return $thereturn;
		}//end function

		function freeResult($queryresult){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_free_result($queryresult);
				break;
			}//end case
			return $thereturn;
		}//end function


		function fieldTable($queryresult,$offset){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_field_table($queryresult,$offset);
				break;
			}//end case
			return $thereturn;
		}//end function


		function fieldName($queryresult,$offset){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_field_name($queryresult,$offset);
				break;
			}//end case
			return $thereturn;
		}//end function
		
		
		function tableInfo($tablename){
			//this function returns a multi-dimensional array describing the fields in a given table
			$thereturn = false;
			switch($this->type){
				case "mysql":
					$queryresult = @ mysql_list_fields($this->schema,$tablename);
					if($queryresult){
						for($offset = 0; $offset < mysql_num_fields($queryresult); ++$offset){
							$name = $this->fieldName($queryresult,$offset);
							$thereturn[$name]["type"] = @ mysql_field_type($queryresult,$offset);
							$thereturn[$name]["length"] = mysql_field_len($queryresult,$offset);
							$thereturn[$name]["flags"] = mysql_field_flags($queryresult,$offset);							
						}
					}
				break;
			}//end case
			return $thereturn;
		}


		function insertId(){
			$thereturn = false;
			switch($this->type){
				case "mysql":
					$thereturn = @ mysql_insert_id($this->db_link);
				break;			
			}
			
			return $thereturn;
		}
		
		function affectedRows(){
			$thereturn = false;
			switch($this->type){
				case "mysql":
					$thereturn = @ mysql_affected_rows($this->db_link);
				break;			
			}
			
			return $thereturn;
		}
		
}//end db class
?>
