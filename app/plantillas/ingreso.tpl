<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
  <head>
    <title>$titulo</title>
    <style>
        #botonesPanel{
            display:none;
           }
           #mapa > div {
    margin: 0 auto;
}
      
    </style>
    <meta http-equiv=\"Content-Type' content='text/html; charset=UTF-8\">
        $css
        $javascript
        
  </head>
  <body style = \"background: url(images/intro.png) center top no-repeat;\" onLoad = \"cargarSimple('$moduloCarga','mapa','/inicio.js','inicio.css')\">

<div id = \"contenido\" style = \"height:400px;\">


<div id = \"mapa\"  style = \"height:250px;margin-top:100px;  \">
    
    </div>
</div>

    {$errores}
  </body>
  
</html>