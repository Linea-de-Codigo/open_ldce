<?
ini_set('display_errors','0');
if (isset($_REQUEST)){
	

echo "Creando archivo de InformaciÃ³n de la Implementacion..<br />";
if (file_exists('../app/conf.php')) 
	
	if(!unlink("../app/conf.php"))
		die("Error, no se puede escribir el archivo de configuracion");
		
  $ar=fopen("../app/conf.php","a") or
  die("Problemas en la creacion archivo 1..<br />");
    $datosInfoModulo = '
    <?
	define("__baseDatos", "database");	
	define("__lenguaje", "es");	
	define("__ArchivoVariables", "'.$_POST['absoluta'].'/variables.xml");
	define("__superCore", "'.$_POST['absoluta'].'/core/");
	define("__negocio", "'.$_POST['absoluta'].'/public/");
    ?>
    ';
    
  fputs($ar,$datosInfoModulo);
  fclose($ar);
  
  echo "Archivo de Información la implementacion creado..<br />";

echo "Creando archivo de variables<br />";

if (file_exists("../variables.xml")) 
	if (!unlink("../variables.xml"))
		die("Error, no se puede escribir el archivo de configuracion");
		
  $ar=fopen("../variables.xml","a") or
  die("Problemas en la creacion archivo 2..<br />");
    $datosInfoModulo = '
    <?xml version="1.0" encoding="UTF-8"?>
<variables>
    <titulo>Open LDCE</titulo>
    <version>1.2</version>
    <copyrigh>Linea de Codigo</copyrigh>
    <carpetas>
        <aplicacion>'.$_POST['absoluta'].'/app/</aplicacion>
        <css>css/</css>
        <imagenes>images/</imagenes>
	
	<framework>../public/</framework>
	<javascript>javascript/</javascript>
        <plantillas>plantillas/</plantillas>	
	
	<idioma>es</idioma>
	
    </carpetas>
    <base_datos accion = "editar">
        <motor>mysql</motor>
        <usuario>'.$_POST['userEdita'].'</usuario>
        <clave>'.$_POST['passEdita'].'</clave>
    </base_datos>
    <base_datos accion = "seleccionar">
        <motor>mysql</motor>
 <usuario>'.$_POST['userSelecciona'].'</usuario>
         <clave>'.$_POST['passSelecciona'].'</clave>

    </base_datos>
    <base_datos accion = "elminar">
        <motor>mysql</motor>
 <usuario>'.$_POST['userElimina'].'</usuario>
         <clave>'.$_POST['passElimina'].'</clave>

    </base_datos>

</variables>

    ';
    
  fputs($ar,$datosInfoModulo);
  fclose($ar);
  
  echo "Archivo de Información la implementacion creado..<br />";

}
?>

<html>
<head>
<title>Instalador Open LDCE</title>
</head>
<body>
<h1>Instalador Open LDCE</h1>
<form method = "post">
<table>
	<tr>
	<th>Ruta Abosluta del Sistema</th>
	<td><input type = "input" name = "absoluta" />
	</tr>
	<tr>
	<th>Nombre Base de datos</th>
	<td><input type = "input" name = "baseDatos" />
	</tr>
	<tr>
	<th>Usuario MYSQL - Edita -</th>
	<td><input type = "input" name = "userEdita" />
	</tr>
	<th>Contrasena MYSQL - Edita -</th>
	<td><input type = "input" name = "passEdita" />
	</tr>
	<tr>
	<th>Usuario MYSQL - Selecciona -</th>
	<td><input type = "input" name = "userSelecciona" />
	</tr>
	<th>Contrasena MYSQL - Selecciona -</th>
	<td><input type = "input" name = "passSelecciona" />
	</tr>
	<tr>
	<th>Usuario MYSQL - Elimina -</th>
	<td><input type = "input" name = "userElimina" />
	</tr>
	<th>Contrasena MYSQL - Selecciona -</th>
	<td><input type = "input" name = "passElimina" />
	</tr>
</table>
<input type = "submit" value = "Configurar">
</form>