<?php

session_start();
class acceso{
	
	function __construct(){
		
	}
}

      
class WebCamUpload {
	var $input 			= null;
	var $serverRequest 	= null;

   
	function __construct($serv,$in){ 
		$this->input = $in;
		$this->serverRequest = $serv;
	}
	function saveImage($dir='') {
	
	
		//$jpg = str_replace('##','\#\#',mysql_escape_string($jpg));
                
	
                
		$result = file_put_contents($dir.$_GET['nombre'], $this->input );
		if (!$result) {
			throw new Exception("No se pudo guardar la imagen, revisa permisos");
			exit();
		}
		return 'http://' . $this->serverRequest['HTTP_HOST'] . dirname($this->serverRequest['REQUEST_URI']) . '/' . $filename;
	}
}

try {
	$cam = new WebCamUpload($_SERVER,file_get_contents('php://input'));
	echo $cam->saveImage($_GET['carpeta']."/");
}catch(Exception $e) {
	echo $e->getMessage();
}

?>