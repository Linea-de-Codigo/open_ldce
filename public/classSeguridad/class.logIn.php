<?php    

session_start();

    require_once  __superCore."classSeguridad/class.logIn.php"; 
    require_once  __superCore."compartido/class.cargar_variables.php";
    require_once  __superCore."compartido/class.xml_procesador.php";
    require_once __superCore."classPresentacion/class.controles.php";
    require_once  __superCore."classNegocio/tabla.php";
    
    $globales = new cargar_variables();
    $conDatosEdita = new db($globales, __baseDatos,"seleccionar");
    $administrarTabla = new tabla("usuario");
    $comprobarUsuario = new comprobarUsuario($conDatosEdita,$administrarTabla);
    $declararSesiones = array("idUsuario"=>"idUsuario","user"=>"nombreUsuario","perfil"=>"idPerfil","empleado"=>"idEmpleado","idDependencia"=>"idDependencia");
    $retotono = $comprobarUsuario-> retornaValidacion($_POST['usuario'],$_POST['contrasena'],$declararSesiones,__baseDatos);


    if (isset($retornar) && $retornar == 1){
        $desde = $_SERVER['HTTP_REFERER'];
        header ("Location: ".$desde."&resultado=$retotono");
    }else{
        echo $retotono;
    }

    
?>
