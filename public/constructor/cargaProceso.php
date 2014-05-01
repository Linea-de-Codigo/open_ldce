<?php
$baseDatosComponente = new compatibilidadDB;
$filaComponenteProceso = $baseDatosComponente->arrayRegistros("proceso", "*", " inner join componente on proceso.idComponente = componente.idComponente where idProceso = ".$_REQUEST['proceo']);

echo json_encode($filaComponenteProceso[0]);

?>
