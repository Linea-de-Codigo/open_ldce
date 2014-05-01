<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
  <head>
    <title>$titulo</title>
    <meta http-equiv=\"Content-Type' content='text/html; charset=UTF-8\">
<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href = \"css/indice.css\" />

        $css
        $javascript
	<script type=\"text/javascript\" src = \"apoyo/cargaSVG.js\"></script>
<style>
    #idSolicitud_m{
        width:500px;
        font-size:16px;
        font-weight:bold;
    }
    #mapa {
        
    }
</style>
  </head>
  <body style = \"margin:0;padding:0;background: url(images/fonfolinea.png) center top repeat-y;\" onLoad = \"cargar('$moduloCarga')\">
  
     <div style = \"background: url(images/cideccTop.png) center top no-repeat;\" id = \"contenido\" align = \"left\">
    </div>
    {$errores}
    
  </body>
</html>