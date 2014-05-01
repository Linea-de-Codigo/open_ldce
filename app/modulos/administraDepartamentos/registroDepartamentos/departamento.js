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
    if($("accion").value == "guardar"){
        if ($("Grilladependencia0")) { $("Grilladependencia0").destroy();}
    }else{
        if ($(Tabladependencia0)){ new SortingTable( 'Tabladependencia0' ); }
    }
    if ($("Tabladependencia1")){ new SortingTable( 'Tabladependencia1' ); }   
}); 