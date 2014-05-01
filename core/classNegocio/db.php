<?php 
class appError
{
 	var $numeroerror;
	var $descripcionerror;
        var $error_motor;
        var $control;
	
	function __construct()
	{
            $this->control = new controles();
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
				$this->descripcionerror="Ambig&uuml;edad en Columna en la Condici&oacute;n.";
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
				$this->descripcionerror="El n&uacute;mero de columnas no corresponde con la cantidad que se tratan de registrar.";
				break;
				case 1205:
				$this->descripcionerror="Tiempo de espera agotado para la operaci&oacute;n.";
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
			return array($this->control->div_error($this->descripcionerror,$this->error_motor,$this->numeroerror),0) ;
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
                function  __construct($informacion,$shema=NULL,$accion){
                    if ($shema==NULL){
                     //   die("Operacion no permitida");
                    }
                    $this->dbuserremoto = $informacion->Usuario[$accion];
                    $this->dbpassremoto = $informacion->Clave[$accion];
                    $this->type=$informacion->Motor[$accion];
                    $this->schema = $shema;
                }
		function db($connect = true){
			$_SESSION['hostnameremoto']=$this->hostnameremoto;
			$hostname = "";
                        $schema = "";
                        $pconnect = "";
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
                        global $_SESSION;
                
			// This functions connects to the database.  It uses pconnect if the variable is set;
                        switch($this->type){
				default:
				case "mysql":
                                $this->db_link = mysql_connect($this->hostnameremoto,$this->dbuserremoto,$this->dbpassremoto);
                                break;
                                case "postgres":
                                    $this->db_link = pg_connect("host=".$this->hostnameremoto." dbname=".$this->schema." user=".$this->dbuserremoto." password=".$this->dbpassremoto."");
                        }            
                          
			if(!($this->db_link))
			{
				$_SESSION['hostnameactual']=$this->hostnamelocal;
				$this->db_link = mysql_connect($this->hostnamelocal,$this->dbuserlocal,$this->dbpasslocal);
			}
			else
			{
				
			}
			if(!$this->db_link){
                        
				$error = new appError(mysql_errno($this->db_link),mysql_error($this->db_link));
				$error->mostrarError();
				return false;
			} else
                            
				return $this->db_link;
		}

		
		function selectSchema($schema=NULL){
                    if ($this->type=="mysql"){
                    if($schema!=NULL)
				$this->schema=$schema;
			if(! @ mysql_select_db($this->schema,$this->db_link)){
				$error = new appError(mysql_errno($this->db_link));
				$error->mostrarError();
				return false;
			} else
				return true;							
                        
                    }else {
                            return true;					
                    }           
		}


		function query($sqlstatement,$cons=0){
                    //echo $sqlstatement;
                    $sqlstatement = str_replace("\'","'",$sqlstatement);
                    $control = new controles();
                    $errors = new appError();
			switch($this->type){
				case "mysql":
					if(!isset($this->db_link)) 
						if(!$this->dataB()) die($this->error);
                                                //echo $sqlstatement;
					$queryresult=  @ mysql_query($sqlstatement,$this->db_link);
                                        //echo mysql_errno($this->db_link );
					if(mysql_errno($this->db_link) <> 0){
						
						$errors->numeroerror = mysql_errno($this->db_link);
                                                $errors->error_motor = mysql_error($this->db_link);
						//echo mysql_error($this->db_link);
						return $errors->mostrarError();
					}else{
                                            if ($cons==0)
                                                return array($control->div_msj("Guardado Correctamente"),1) ;
                                                else
                                                return $queryresult;
                                        }
                                        //echo "Paso";
				break;
                                case "postgres":
                                    //echo $sqlstatement;
                                    $queryresult = @ pg_query($this->db_link, $sqlstatement);
                                    if (pg_errormessage($this->db_link)){
                                          //return pg_errormessage($this->db_link);
                                        $errors->numeroerror =  pg_errormessage($this->db_link);
                                        return array($control->div_error($errors->numeroerror,$sqlstatement),0);
                                    }else{
                                        if ($cons==0)
                                                return array($control->div_msj("Guardado Correctamente"),1) ;
                                                else
                                                 return $queryresult;
                                    }
                                    break;
			}//end case
			
		}//end function


		function setEncoding($encoding = "utf8"){

			switch($this->type){
				case "mysql":			
					@ mysql_query("SET NAMES ".$encoding, $this->db_link);
					break;
                                    case "postgres":
                                        pg_query("SET NAMES ".$encoding, $this->db_link);
                                        
					
			}//endswitch

		}//end method


		function numRows($queryresult){
                    $numrows=NULL;
			switch($this->type){
				case "mysql":
					$numrows=@ mysql_num_rows($queryresult);
					if(!is_numeric($numrows)){
						$error = new appError(mysql_errno($this->db_link));
						$error->mostrarError();
						return false;
					}
				break;
                                case "postgres":
                                    $numrows= pg_num_rows($queryresult);
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
                                case "postgres":
                                    $row= pg_fetch_assoc($queryresult);
				break;
			}//end case
			return $row;
		}//end function

		function fetchRow($queryresult){
			//Fetches associative array of current row
			switch($this->type){
				case "mysql":
					$row=@ mysql_fetch_row($queryresult);
                                    case "postgres":
                                        $row= pg_fetch_row($queryresult);
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
                            case "postgres":
                                $row = pg_fetch_object($queryresult);
                                break;
			}//end case
			return $row;
		}//end function


		function seek($queryresult,$rownum){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_data_seek($queryresult,$rownum);
				break;
                            case "postgres":
                                $thereturn = "No disponible";
                                break;
			}//end case
			return $thereturn;
		}//end function


		function numFields($queryresult){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_num_fields($queryresult);
				break;
                            	case "postgres":
					$thereturn= pg_num_fields($queryresult);
				break;
			}//end case
			return $thereturn;
		}//end function

		function freeResult($queryresult){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_free_result($queryresult);
				break;
                            case "postgres":
                                $thereturn=pg_free_result($queryresult);
                                break;
			}//end case
			return $thereturn;
		}//end function


		function fieldTable($queryresult,$offset){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_field_table($queryresult,$offset);
				break;
                             case "postgres":
                                $thereturn=pg_field_table($queryresult,$offset);
                                break;
			}//end case
			return $thereturn;
		}//end function


		function fieldName($queryresult,$offset){
			switch($this->type){
				case "mysql":
					$thereturn=@ mysql_field_name($queryresult,$offset);
				break;
                             case "postgres":
                                 $thereturn = pg_field_name($queryresult,$offset);
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
                             case "postgres":
                                 $thereturn ="";
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
                            case "postgres":
                                 $thereturn = pg_affected_rows($this->db_link);
                                 break;
			}
			
			return $thereturn;
		}
		
}//end db class

?>