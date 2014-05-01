window.addEvent('domready', function() {
	
	$('contrasena').addEvent('keydown', function(event){
		
		if (event.key == 'enter'){
				$("Ingresar").onclick();
		}
	});
});
