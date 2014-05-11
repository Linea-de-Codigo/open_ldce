var diente = new Class({
	
	initialize: function(svg){
	
		this.svg = svg;
		
	},
	renderiza:function(Numero,Name,padre,funcionClic){
		
		var RequestSVG = new Request({
		    url: this.svg , method: 'get',
		    onSuccess: function(responseText){
			var myDiente = new Element('div', {
				id:Name,
				html:responseText
			});
			myDiente.inject($(padre),'bottom');
			$$('#'+Name+'  #textoDiente').set('html',Numero);
			
			$$('#'+Name+'  svg').set('diente',Name);
			$$('#'+Name+' .Oclusal').addEvent('click', funcionClic);
			$$('#'+Name+' .Palatino').addEvent('click', funcionClic);
			$$('#'+Name+' .Lingual').addEvent('click', funcionClic);
			$$('#'+Name+' .Cervical').addEvent('click', funcionClic);
			$$('#'+Name+' .Distal').addEvent('click', funcionClic);
			$$('#'+Name+' .Mesial').addEvent('click', funcionClic);
			$$('#'+Name+' .Vestibular').addEvent('click', funcionClic);
                        
                      
		    }
		});
		RequestSVG.send();
	}
});