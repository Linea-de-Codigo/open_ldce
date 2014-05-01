window.addEvent('domready', function(){
    
 var myBoton = new Element('input', {id: 'botonProspectp',value:'Cambiar Clave', type:'button'});
 
myBoton.addEvent('click', function(){
        if ($("idUsuario").value != ""){
            carga_div("index2.php","Cambiar Clave",400,180,"carpeta=seguridad&archivo=cambiarClave","idUsuario="+$("idUsuario").value,101);
        }else{
            alert("Seleccione un Usuario para cambiarle la clave");
        }
    });
    
    myBoton.inject($("respuesta").getParent());
    $$('input').addEvents({
		change: function(control){
                  if (this.get("class")=="requeridoError"){
			this.set("class","requerido");
                  }
		}
    });
    
    
 });
 function cambiarClave(){
     if ($('clave').value == $('confirmar').value){
         myElement = $('msjClave');
        var myRequest = new Request({
            url: 'index2.php',
            method: 'get',
            onRequest: function(){
                myElement.set('html', 'Cambiando Clave');
            },
            onSuccess: function(responseText){
                myElement.set('html', responseText);
            },
            onFailure: function(){
                myElement.set('text', 'No se pudo enviar la peticion');
            }
        }).send("carpeta=seguridad&archivo=cambiarClave&idUsuario="+$("idUsuario").value+"&clave="+$("clave").value+"&anterior="+$("anterior").value);
        }else{
            alert("No coinciden las claves.");
        }
    }