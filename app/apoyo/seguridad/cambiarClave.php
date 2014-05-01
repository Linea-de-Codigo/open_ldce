<?php
    $consultaUsuario  = new compatibilidadDB();
    
    if(isset ($_REQUEST['clave']) ) {
        $consultaClaveActual = $consultaUsuario->arrayRegistros("usuario", "*", " where idUsuario = ".$_REQUEST['idUsuario']);
       // echo $consultaClaveActual[0]['contrasena'];
        if ($consultaClaveActual[0]['contrasena']== "" || $consultaClaveActual[0]['contrasena']==md5($_REQUEST['anterior'])){
             $actualizarClave = $consultaUsuario->ejecutaSQL("UPDATE `usuario` SET `contrasena` = MD5( '".$_REQUEST['clave']."' ) WHERE `usuario`.`idUsuario` =".$_REQUEST['idUsuario'].";", 0);
             echo $actualizarClave[0];
        }else{
          echo "<div align = \"center\" style = \"color:#ff0000;\">No Coinciden las Claves</div>";
        }
        die();
    }
        
?>
<?=controles::campo_oculto("idUsuario",$_REQUEST['idUsuario']); ?>
<table align="center" >
    <tr>
        <th>Contrase&ntilde;a Anterior</th><td><?=controles::campo_password("anterior",10,""); ?></td>
    </tr>
    <tr>
        <th>Nueva Contrase&ntilde;a</th><td><?=controles::campo_password("clave",10,""); ?></td>
        </tr>
        <tr>
        <th>Confirmar Contrase&ntilde;a</th><td><?=controles::campo_password("confirmar",10,""); ?></td>
        </tr>
        <tr>
        <td></td><td><?=controles::boton("Cambiar","Cambiar","cambiarClave()") ?></td>
        
    </tr>
</table>
<div id ="msjClave"></div>