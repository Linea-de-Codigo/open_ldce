<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="es">
<head>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery.webcam.js"></script>
		<script type="text/javascript" language="JavaScript">
		
		$(function() {
			$('#webcam').createWebCam();
			$('#capture').click(function() {
				
				 $('#webcam').getWebCam().capture(function(json){
					if(json.type=="success") {
						//alert('URL: '+json.msg);
						console.log('URL: '+json.msg);
					} else if(json.type == "error") {
						//alert("Incorrecto");
						console.log('ERROR: '+json.msg);
					}
				});
			});
			$('#reset').click(function() {
				 $('#webcam').getWebCam().reset();
			});
			$('#configure').click(function() {
				 $('#webcam').getWebCam().configure();
			});
		});
	</script>
	
</head>
<body>
	<div id="webcam"></div>
        <input type ="hidden" value ="<?=$_GET['nombre'] ?>" name ="nombre" id="nombre" >
        <input type ="hidden" value ="<?=$_GET['carpeta'] ?>" name ="carpeta" id="carpeta" >
	<input type = "button" id="capture" value = "Tomar">
	<input type = "button" id="reset" value = "Nueva Toma">
	
	<!--<a id="configure">Configure</a>-->
</body>
</html>