window.addEvent('domready', function(){
        $$('input').addEvents({
                    change: function(control){
                      if (this.get("class")=="requeridoError"){
                            this.set("class","requerido");
                      }
                    }
        });
    });        
