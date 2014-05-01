<?php

include ("../../info.jpg");

$buscarRegistro= new ArrayObject;
$color = "#60ac64";

require_once  "/home/pidem/superCore/compartido/class.cargar_variables.php";
require_once  "/home/pidem/superCore/compartido/class.xml_procesador.php";
require_once "/home/pidem/superCore/classPresentacion/class.controles.php";
require_once  "/home/pidem/superCore/classNegocio/tabla.php";
require_once "/home/pidem/superCore/classPresentacion/class.controles.php";

$globales = new cargar_variables();

   $conDatosEdita = new db($globales, __baseDatos,"editar");
   $conexion = $conDatosEdita->connect(true);
   $conexion = $conDatosEdita->db(true);
   
   $queryresult =  $conDatosEdita->query("select registroInmueble.*, solicitantes.*, solicitud.*, tipoInmueble.descripcion as tipoDescripcion  from solicitantes 
       inner join solicitud on solicitantes.idSolicitud = solicitud.idSolicitud
       inner join registroInmueble on registroInmueble.idRegistroInmueble = solicitud.idRegistroInmueble
       inner join tipoInmueble on tipoInmueble.idTipo = registroInmueble.tipoInmueble
       where idSolicitante = '".$_REQUEST['idSolicitante']."'",1);

    

                        $buscarRegistro=$conDatosEdita->fetchArray($queryresult);
                        
   $queryresultProp =  $conDatosEdita->query("select * from tercero where idTercero = ".$buscarRegistro['idTerceroPropietario'],1);
   
   $buscaPropietario = $conDatosEdita->fetchArray($queryresultProp);
   
   $queryresultSol =  $conDatosEdita->query("select * from tercero where idTercero = ".$buscarRegistro['idTercero'],1);
   
   $buscaSolicitante = $conDatosEdita->fetchArray($queryresultSol);

$tipoSolicitante = NULL;

switch ($buscarRegistro['tipoSolicitante']){
    case 0:
        $tipoSolicitante = "INQUILINO";
        break;
    case 1:
        $tipoSolicitante = "CODEUDOR 1";
        break;
    case 2:
        $tipoSolicitante = "CODEUDOR 2";
        break;
    case 3:
        $tipoSolicitante = "CODEUDOR 3";
        break;
    case 4:
        $tipoSolicitante = "CODEUDOR 4";
        break;
}

//if ($buscarRegistro['tipoSolicitante'] == 0){ $tipoSolicitante = "INQUILINO";} else{ if($buscarRegistro['tipoSolicitante'] == 1) $tipoSolicitante = "CODEUDOR 1"; else $tipoSolicitante = "CODEUDOR 2"; }
    

$salida="<html>
<head>
    <meta http-equiv=\"Content-Type' content='text/html; charset=UTF-8\">
    <style>
#encabezado td{ border:none; }
    *{ font-family: dejavu serif condensed;  }
    td{ border: 0.5px solid #333333;  }
        table{border-collapse: collapse;}
        #tituloPrincipal {color:$color ; font-size:26px; padding-top:15px;}
        .textoVerde{ color:$color ; text-align:center; font-weight:bold; }
        .tdVerde{ background-color: $color; color:#ffffff; font-weight:bold; border-color: $color; text-align:center;}
        .tdVerdeGrande{ background-color: $color; text-align:center; color:#ffffff;  font-size:14px; border: 1px solid $color;}
</style>
</head>
<body style = \"margin:20px;font-size:8px;text-transform: uppercase;    \">
    
    <table id= \"encabezado\" width = \"100%\"  >
        <tr><td  width = \"38%\"  ><img  src = \"../../images/SINCRA.jpg\" />
        <td width = \"24%\" style = \"text-align: center; padding-top:30px;\" >Nit. 830.510.181-4<br />Regimen Com&uacute;n</td>
        </td><td  width = \"38%\"><div id = \"tituloPrincipal\" align = \"right\">".$buscarRegistro['tipoSolicitante']."</div>
              
        <table style = \"border: 1px solid $color;\"  width = \"100%\" >
                 <tr><td class = \"tdVerdeGrande\">Clave</td><td class = \"tdVerdeGrande\">No. SOLICITUD</td></tr>
                <tr><td style = \"border: 1px solid $color;\" >".$buscarRegistro['clave']."</td><td style = \"border: 1px solid $color;\">".$buscarRegistro['idSolicitud']."</td></tr>
            </table>
</td>
        </tr>
        <tr>
            <td colspan = \"2\"><span class = \"textoVerde\">SOLICITUD DE ARRENDAMIENTO PARA PERSONAS NATURALES O JURIDICAS</span></td>
           
        </tr>
        </table>
     
<table   width = \"100%\" >
     <tr><td class = \"tdVerde\" >DIRECCION DEL INMUEBLE A ARRENDAR</td><td colspan = \"3\">".$buscarRegistro['direccion']."</td></tr>
         <tr>
            <td width = \"25%\" class = \"tdVerde\" >CANON</td><td>".$buscarRegistro['canon']."</td>
            <td width = \"25%\" >ADMINISTRACION</td><td>".$buscarRegistro['administracion']."</td>
         </tr>
         <tr>
            <td width = \"25%\" class = \"tdVerde\" >NOMBRE PROPIETARIO</td><td>".$buscaPropietario['nombre']." ".$buscaPropietario['apellido']."</td>
            <td width = \"25%\" >TELEFONO</td><td>".$buscaPropietario['telefono']."</td>
         </tr>
         <tr>
            <td width = \"25%\" class = \"tdVerde\" >INMUEBLE SOLICITADO</td><td colspan = \"3\" >".$buscarRegistro['tipoDescripcion']."</td>
            
         </tr>
         
        
        <tr>
            <td width = \"25%\"  >USO QUE SE DARA INMUEBLE</td><td>".$buscarRegistro['usoDaraInmueble']."</td>
            <td width = \"25%\" >No. PERSONAS</td><td>".$buscarRegistro['noPersonas']."</td>
         </tr>

</table>
<br/>
<div class = \"textoVerde\">DATOS PERSONALES O DE LA EMPRESA</div>
<table   width = \"100%\" > 
        <tr>
            <td width = \"40%\" colspan = \"2\"  >NOMBRE COMPLETO O RAZON SOCIAL</td><td>".$buscaSolicitante['nombre']." ".$buscaSolicitante['apellido']."</td>
            <td width = \"20%\" >C.C. o NIT </td><td>".$buscaSolicitante['cedulaTercero']."</td>
         </tr>
         <tr>
             <td>ESTADO CIVIL: ".$buscarRegistro['estadoCivil']."</td>
             <td>PERSONAS A CARGO: ".$buscarRegistro['personasCargo']."</td>
             <td>FECHA NACIMIENTO: ".$buscarRegistro['fechaNacimiento']."</td>
             <td>No. CELULAR: ".$buscaSolicitante['celular']."</td>
             <td>DIRECCION ".$buscaSolicitante['dirCorrespondencia']."</td>
             
         </tr>
         <tr>
             <td colspan = \"2\">PROFESION (OFICIO PERSONA JURIDICA): ".$buscaSolicitante['profesion']."</td>
             <td colspan = \"2\">DIRECCION ACTUAL ".$buscaSolicitante['dirCorrespondencia']."</td>
             
             <td>TELEFONO: ".$buscaSolicitante['telefono']."</td>
             
         </tr>
         
         <tr>
             <td colspan = \"2\">NOMBRE DEL CONYGUE(REPRESENTANTE LEGAL PERSONA JURIDICA) ".$buscarRegistro['conyugueRepresentante']."</td>
             <td colspan = \"2\">C.C. No. ".$buscarRegistro['ccConyugeRepresentante']."</td>
             
             <td>TELEFONO ".$buscarRegistro['telefonoCOnyugeRepresentante']."</td>
             
         </tr>
        <tr>
             <td colspan = \"2\">ENTIDAD EN LA  QUE LABORA EL CONYUGUE ".$buscarRegistro['entadadTrabajaConyuge']."</td>
             <td >SUELDO CONYUGUE ".$buscarRegistro['sueldoConyuge']."</td>
             <td >GASTOS MENSUALES FAMILIARES ".$buscarRegistro['gastosMensuales']."</td>
             <td>E-MAIL ".$buscaSolicitante['email']."</td>
             
         </tr>
         <tr>
             <td colspan = \"2\">SI EL INMUEBLE EN EL RESIDE ES ARRENDADO, INDIQUE EL NOMBRE DEL PROPIETARIO O DE LA AGENCIA</td>
             <td colspan = \"2\">".$buscarRegistro['referenciaArrendador']."</td>
             <td>TELEFONO ".$buscarRegistro['telefonoArrendador']."</td>
             
         </tr>
</table>
&nbsp;
<table width = \"100%\" >
    <tr>
        <td width = \"50%\">
            <table width = \"100%\">
                <tr>
                <td colspan = \"2\" class = \"tdVerde\">SI ES EMPLEADO DILIGENCIE ESTE ESPACIO</td>
                </tr>
                <tr><td>ENTIDAD DONDE TRABAJA ".$buscarRegistro['entidadTrabaja']."</td><td>SUELDO ".$buscarRegistro['sueldo']."</td></tr>
                <tr><td>DIRECCION OFICINA ".$buscarRegistro['direccionOficina']."</td><td>TELEFONO".$buscarRegistro['telefonoTrabajo']."</td></tr>
                <tr><td>CARGO ACTUAL ".$buscarRegistro['cargoActual']."</td><td>FECHA INGRESO ".$buscarRegistro['fechaIngreso']."</td></tr>
                <tr><td>OTROS INGRESOS(Anexe Certificaci&oacute;n que acredite Otros Ingresos) ".$buscarRegistro['otrosIngreso']."</td><td>EPS ".$buscarRegistro['eps']."</td></tr>
            </table>
        </td>
        <td width = \"50%\">
            <table width = \"100%\">
                <tr>
                <td colspan = \"3\" class = \"tdVerde\">SI ES INDEPENDIENTE DILIGENCIE ESTE ESPACIO</td>
                </tr> 
                <tr><td colspan = \"3\">ACTIVIDADES O DESCRIPCION DE SU NEGOCIO ".$buscarRegistro['actividadDescripcionNegocio']."</td></tr>
                <tr><td colspan = \"3\" >DIRECCION DEL NEGOCIO ".$buscarRegistro['direccionNegocio']."</td></tr>
                <tr><td>Telefono ".$buscarRegistro['telefonoNegocio']."</td><td>No. EMPLEADOS ".$buscarRegistro['nroEmpleados']."</td><td>No. Reg. MERCANTIL ".$buscarRegistro['nroMercantil']."</td></tr>
                <tr><td colspan = \"2\" >INGRESOS ".$buscarRegistro['ingresosNegocio']."</td><td>EGRESOS ".$buscarRegistro['egresosNegocio']."</td></tr>
            </table>
        </td>

    </tr>
</table>
&nbsp;
<div class = \"textoVerde\">DETALLES DEL INMUEBLE DE SU PROPIEDAD</div>
<table width = \"100%\" >
    <tr>
        <td>DIRECCION DEL INMUEBLE ".$buscarRegistro['direccionInmueble1']." </td>
        <td>CIUDAD ".$buscarRegistro['ciudadInmueble1']." </td>
        <td>No. MATRICULA INMOVILIARIA ".$buscarRegistro['noMatriculaInmoviliaria1']."</td>
        <td>VALOR HIPOTECA ".$buscarRegistro['valorHipoteca1']."</td>
        <td>VALOR COMERCIAL ".$buscarRegistro['valorComercial1']."</td>
    </tr>
    <tr>
        <td>DIRECCION DEL INMUEBLE ".$buscarRegistro['direccionInmueble2']." </td>
        <td>CIUDAD ".$buscarRegistro['ciudadInmueble2']." </td>
        <td>No. MATRICULA INMOVILIARIA ".$buscarRegistro['noMatriculaInmoviliaria2']."</td>
        <td>VALOR HIPOTECA ".$buscarRegistro['valorHipoteca2']."</td>
        <td>VALOR COMERCIAL ".$buscarRegistro['valorComercial2']."</td>
    </tr>
    </tr>
</table>
&nbsp;
<div class = \"textoVerde\">DETALLES DEL VEHICULO DE SU PROPIEDAD</div>
<table width = \"100%\" >
    <tr>
        <td>MARCA ".$buscarRegistro['marcaVehiculo']."</td>
        <td>MODELO ".$buscarRegistro['modelo']."</td>
        <td>PLACA ".$buscarRegistro['placa']."</td>
        <td>MARCA ".$buscarRegistro['marcaVehiculo2']."</td>
        <td>MODELO ".$buscarRegistro['modelo2']."</td>
        <td>PLACA ".$buscarRegistro['placa2']."</td>
    </tr>
</TABLE>
&nbsp;
<div class = \"textoVerde\">REFERENCIAS</div>
<table  width = \"100%\">
    <tr>
        <td  width = \"20%\" class = \"tdVerde\" rowspan = \"2\">FAMILIARES</td>
        <td colspan = \"2\">NOMBRE ".$buscarRegistro['refFamiliarNombre1']."</td>
        <td>PARENTESCO ".$buscarRegistro['refFamiliarParentesco1']."</td>
        <td>TELEFONO FIJO  ".$buscarRegistro['refFamiliarTelFijo1']."</TD>
    </tr>
    <tr>
       <td colspan = \"2\">NOMBRE ".$buscarRegistro['refFamiliarNombre2']."</td>
        <td>PARENTESCO ".$buscarRegistro['refFamiliarParentesco2']."</td>
        <td>TELEFONO FIJO  ".$buscarRegistro['refFamiliarTelFijo2']."</TD>
    </tr>
    <tr>
        <td class = \"tdVerde\" rowspan = \"2\">PARTICULARES</td>
        <td colspan = \"2\">NOMBRE ".$buscarRegistro['refParticularNombre1']."</td>
        <td>TELEFONO ".$buscarRegistro['refParticularTel1']."</td>
        <td>TELEFONO FIJO ".$buscarRegistro['refParticularTelFijo1']."</TD>
    </tr>
    <tr>
        <td colspan = \"2\">NOMBRE ".$buscarRegistro['refParticularNombre2']."</td>
        <td>TELEFONO ".$buscarRegistro['refParticularTel2']."</td>
        <td>TELEFONO FIJO ".$buscarRegistro['refParticularTelFijo2']."</TD>
    </tr>
    <tr>
        <td class = \"tdVerde\" rowspan = \"2\">TARJETAS DE CREDITO</td>
        <td>ENTIDAD:  ".$buscarRegistro['entidad1']."</td>
        <td>NO. TARJETA ".$buscarRegistro['noTarjeta1']."</td>
        <td>SOCIO DESDE ".$buscarRegistro['socioDesde1']."</TD>
        <td>CUPO  ".$buscarRegistro['cupo1']."</TD>
    </tr>
    <tr>
        <td>ENTIDAD:  ".$buscarRegistro['entidad2']."</td>
        <td>NO. TARJETA ".$buscarRegistro['noTarjeta2']."</td>
        <td>SOCIO DESDE ".$buscarRegistro['socioDesde2']."</TD>
        <td>CUPO  ".$buscarRegistro['cupo2']."</TD>
    </tr>
    <tr>
        <td class = \"tdVerde\" rowspan = \"2\">BANCOS O CORPORACIONES</td>
        <td colspan = \"2\">ENTIDAD ".$buscarRegistro['entidadBanco1']."</td>
        <td>NO. CUENTA ".$buscarRegistro['noCuenta1']."</td>
        <td>SUCURSAL ".$buscarRegistro['sucursal1']."</TD>
    </tr>
    <tr>
        <td colspan = \"2\">ENTIDAD ".$buscarRegistro['entidadBanco2']."</td>
        <td>NO. CUENTA ".$buscarRegistro['noCuenta2']."</td>
        <td>SUCURSAL ".$buscarRegistro['sucursal2']."</TD>
    </tr>
    <tr>
    <td colspan = \"5\">TIENE POLIZAS CON LA EQUEIDAD SEGUROS: ".$buscarRegistro['tienePlozaEquidad']."</td>
</tr>
<tr>
    <td colspan = \"5\">
    Expresa e irrevocablmente autorizo a SISTEMA INTEGRAL DE ESTUDIOS E INFORMACIONES CREDITICIAS Y COBRANZAS PARA ARRENDAMIENTO SINCRRA LTDA o a quien represente sus
    derechos, para que obtencgan, de cualquier fuente y reporte a caulquier banco de datos, las informaciones y referencias relativas a mi persona, mis nombres, apellidos y documentos de identificaci&oacute;n, a mi
    comportamiento y cr&eacute;dito comercial, h&aacute;bitos de pago, manejo de mi(s) cuenta(s), bancaria(s) y en general cumplimiento de mis obligaciones pecuniarias.</p>
    <br />
    Autorizo al sistema SISTEMA INTEGRAL DE ESTUDIOS E INFORMACIONES CREDITICIAS Y COBRANZAS PARA ARRENDAMIENTO SINCRRA LTDA., para compartir con las dem&aacute;s entidades
    subordinadas o controladas por la EQUIDAD SEGUROS toda informaci&oacute;n que repose en sus archivos para propositos comerciales y con el finn de recibir  una atenci&oacute;n integral como cliente
    de esta &uacute;ltima.
 
    <table  width = \"100%\" style = \"border:none;\">
<tr><td width = \"30%\" style = \"border:none;\" ></td><td width = \"40%\" style = \"border:none;\"><br /><br /> </td><td width = \"30%\" style = \"border:none;\"></td></tr>
        <tr><td width = \"30%\" style = \"border:none;\" ></td><td width = \"40%\" style = \"border:none;border-top: 1px $color solid;text-align:center;\"> FIRMA / C.C. No.</td><td width = \"30%\" style = \"border:none;\"></td></tr>
    </table>
    

    </td>
</tr>
</table>
</body>";

ini_set('display_errors', '0');  
include ("../../../procesadorPDF/dompdf_config.inc.php");
$dompdf = new DOMPDF(); 
//echo "../../".$_GET['url'].".php";
$dompdf->set_paper('letter');
$dompdf->load_html($salida);

$dompdf->render();
//echo $salida;
$dompdf->stream("solicitud.pdf");
?>
