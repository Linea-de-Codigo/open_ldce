<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
  <head>
    <title>$titulo</title>
    <meta http-equiv=\"Content-Type' content='text/html; charset=UTF-8\">
        $css
        $javascript
        <style >
        #divNota{
            text-align:center;
            padding:10px;
        }
    #botonesPanel {
        position: fixed;
        top: 50px;
        width: 800px;
    }
#contenido{
       width: 860px;
       padding:0px;
}
#Nuevo{
    display:none;
}
#Eliminar{
    display:none;
}
body{
    margin:0;
background:none;
}



        </style>
<script>
    function verInforme(dato){
          if(dato!=''){
           var myRequest = new Request({
            url: 'index2.php', method: 'post',
            onRequest: function(){ divInforme.set('html','Cargando Informacion de Modelo'); },
            onSuccess: function(responseText){  divInforme.set('html',responseText);},
            onFailure: function(){ divInforme.set('html','No se Pudo Cargar la Informacion del Modelo'); }
        });     
        myRequest.send('carpeta=informeEjemplares&archivo=procesaInforme&idEjemplar='+dato);
        }else{
            alert('Ingrese el codigo del animal');
        }
    }
    function imprSelec(nombre){
      var ficha = document.getElementById(nombre);
      var ventimp = window.open(' ', 'popimpr');
      ventimp.document.write( ficha.innerHTML );
      ventimp.document.close();
      ventimp.print( );
      //ventimp.close();
    }
</script>
  </head>
  <body onLoad = \"cargarSimple('$moduloCarga','mapa','tablero/asesores.js','tablero/asesores.css')\">
<div>
<div id = \"contenido\" style = \"height:400px;\">

<div id = \"mapa\"  style = \"height:860px;width:820;padding-left:10px;padding-right:10px;\">
    
    </div>
</div>
</div>
    {$errores}
  </body>
  
</html>
