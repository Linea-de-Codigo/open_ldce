$$('input').addEvents({
            change: function(){
                $$('input.requeridoError').set("class","requerido");
        }
    });
window.addEvent('domready', function(){
    new Fx.Accordion($('campos'), '#campos .separador', '#campos .seccion', {
        display: 0,
        alwaysHide: false
    }); 
}); 

