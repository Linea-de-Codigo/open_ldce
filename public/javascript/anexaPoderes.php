<? 
	echo $_POST['tipoProceso'];
	
	$_GET['accion'] = "buscar";
	$_GET['tabla']= "poderes";
	$filtro = array("tipoProceso"=>$_POST['tipoProceso']);
	$_POST = array();
$_POST['*'] = "";
	include("../../core/negocio/RecibeSolicitud.php");
	
?>
<table width = "100%">	
<thead>	
	<tr><td>Cedula</td><td>Nombre Cliente</td><td>Referencia</td></tr>
	<tbody>
</thead>	
 <?
    
     if ($buscarRegistro){
            foreach ($buscarRegistro as $key1 => $value) { 
	    echo "<tr class = \"listas\"><td>".$value['ccCliente']."</td><td></td><td>".$value['referencia']."</td></tr>";
         ?>
                
    <? 
            }
    }else{
        echo "No se encontro ningun poder";
    } ?>         
    </tbody>
</table>