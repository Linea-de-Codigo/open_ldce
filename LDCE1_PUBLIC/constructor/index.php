<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        	<script language="Javascript" type="text/javascript" src="editorCodigo/edit_area/edit_area_full.js"></script>
	<script language="Javascript" type="text/javascript">
		// initialisation
		editAreaLoader.init({
			id: "codigoXML"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
			,language: "en"
			,syntax: "xml"	
		});
           </script>
        <title></title>
    </head>
    <body>
        <form method ="post">
            <table>
                <tr><td>Usuario</td><td><input value ="<?=$_REQUEST['usuario'] ?>" type ="text" name ="usuario" id ="usuario" /></td></tr>
                <tr><td>Contraseña</td><td><input value ="<?=$_REQUEST['contrasena'] ?>" type ="password" name ="contrasena" id ="contrasena" /></td></tr>
                <tr><td>Base de datos</td><td><input value ="<?=$_REQUEST['baseDatos'] ?>" type ="text" name ="baseDatos" id ="baseDatos" /></td></tr>
                <tr><td>Tabla</td><td><input value ="<?=$_REQUEST['tabla'] ?>" type ="text" name ="tabla" id ="tabla" /></td></tr>
                <tr><td>Llave Principal</td><td><input type ="llavePrincipal" name ="llavePrincipal" id ="llavePrincipal" value ="<?=$_REQUEST['llavePrincipal'] ?>" /></td></tr>
                <tr><td colpan ="2"><input type ="submit" value="Crear"  /></td></tr>
            </table>
        </form>
        <?php
        
      if (isset ($_REQUEST['baseDatos'])){  
        $contasena = $_REQUEST['contrasena'];
        $usuario = $_REQUEST['usuario'];
        $servidor = "localhost";
        $baseDatos = $_REQUEST['baseDatos'];
        $tabla = $_REQUEST['tabla'];
        $llavePrincipal = $_REQUEST['llavePrincipal'];
        $columnas = "2";
        
        $conexion = mysql_connect($servidor, $usuario, $contasena);
        mysql_select_db($baseDatos, $conexion);
        
        $Sql2 ="DESCRIBE ".$tabla;
        $result2 = mysql_query( $Sql2 ) or die("No se puede ejecutar la consulta: ".mysql_error());
        $salida = "<textarea id =\"codigoXML\"  cols='140' rows='30'><?xml version=\"1.0\" encoding=\"UTF-8\"?>
<formularios >
<formulario id = \"1\" columas = \"2\" ancho = \"95%\"  >
<titulo>$tabla</titulo>\n";
        $salida.="<datos tablaBuscar=\"$tabla\" tabla = \"$tabla\" llave = \"$llavePrincipal\"  guardar = \"1\" eliminar = \"1\" />\n";
        while($Rs2 = mysql_fetch_array($result2)) {
            $campo = $Rs2['Field'];
            $salida.= "         <campo nombre=\"$campo\" tipo =\"texto\"  listar = \"1\"  guardar = \"1\" >$campo</campo>\n";
        }
        echo $salida."
         </formulario>
</formularios></textarea>";
      }        
        ?>
    </body>
</html>
