window.addEvent('domready', function() {
document.oncontextmenu = function(event){ 
	
	if ($("menuContx")){ $("menuContx").dispose(); }
	
	var menuCont = document.createElement("div");
	menuCont.setStyle("left",event.clientX);
	menuCont.setStyle("top",event.clientY);
	menuCont.set("id","menuContx");
	menuCont.setStyle("position","absolute");
	menuCont.set("html","<div onclick = \"nuevaPestana()\">Nueva Pesta&ntilde;a</div><div onclick = \"cerrarSession.onclick()\">Cerrar Sesion</div><div onclick = \"recargarFormulario()\">Recargar Formulario</div><div onclick = \"soporteTecnico()\">Soporte</div><div onclick = \"acercade()\">Acerca de...</div>");
	var contenido = $$("body>div")[0];
	contenido.appendChild(menuCont);
	return false
	
}
});
function actualizaCampo(valor,campo,tabla,llave,valorLlave){
     variables = campo+"="+valor+"&"+llave+"="+valorLlave;
     recibeid("core/negocio/RecibeSolicitud.php","llave="+llave+"&tabla="+tabla+"&accion=editar",variables,"respuesta","cambia_estado","","",llave);
}
document.onclick = function(){
	if ($("menuContx")){ $("menuContx").dispose(); }
}
function nuevaPestana(){
 window.open( 'index.php' );
}
function recargarFormulario(){
		if ($('esteItem')){
			esteItem.onclick();
		}
}
function acercade(){
	cargarBusqueda('apoyo/plugins/acerca.php','A Cerca de Galaxy SIO',900,700,'','');	
}
function soporteTecnico(){
		cargarBusqueda('apoyo/plugins/soporte.php','Abrir Ticket de Soporte',900,700,'','');
}
(function(){
var getStylesList = function(styles, planes){
	var list = [];
	Object.each(planes, function(directions){
		Object.each(directions, function(edge){
			styles.each(function(style){
				list.push(style + '-' + edge + (style == 'border' ? '-width' : ''));
			});
		});
	});
	return list;
};
var calculateEdgeSize = function(edge, styles){
	var total = 0;
	Object.each(styles, function(value, style){
		if (style.test(edge)) total = total + value.toInt();
	});
	return total;
};
Element.implement({
	measure: function(fn){
		var visibility = function(el){
			return !!(!el || el.offsetHeight || el.offsetWidth);
		};
		if (visibility(this)) return fn.apply(this);
		var parent = this.getParent(),
			restorers = [],
			toMeasure = [];
		while (!visibility(parent) && parent != document.body){
			toMeasure.push(parent.expose());
			parent = parent.getParent();
		}
		var restore = this.expose();
		var result = fn.apply(this);
		restore();
		toMeasure.each(function(restore){
			restore();
		});
		return result;
	},
	expose: function(){
		if (this.getStyle('display') != 'none') return function(){};
		var before = this.style.cssText;
		this.setStyles({
			display: 'block',
			position: 'absolute',
			visibility: 'hidden'
		});
		return function(){
			this.style.cssText = before;
		}.bind(this);
	},
	getDimensions: function(options){
		options = Object.merge({computeSize: false}, options);
		var dim = {x: 0, y: 0};
		var getSize = function(el, options){
			return (options.computeSize) ? el.getComputedSize(options) : el.getSize();
		};
		var parent = this.getParent('body');
		if (parent && this.getStyle('display') == 'none'){
			dim = this.measure(function(){
				return getSize(this, options);
			});
		} else if (parent){
			try { //safari sometimes crashes here, so catch it
				dim = getSize(this, options);
			}catch(e){}
		}

		return Object.append(dim, (dim.x || dim.x === 0) ? {
				width: dim.x,
				height: dim.y
			} : {
				x: dim.width,
				y: dim.height
			}
		);
	},

	getComputedSize: function(options){
		//<1.2compat>
		//legacy support for my stupid spelling error
		if (options && options.plains) options.planes = options.plains;
		//</1.2compat>

		options = Object.merge({
			styles: ['padding','border'],
			planes: {
				height: ['top','bottom'],
				width: ['left','right']
			},
			mode: 'both'
		}, options);

		var styles = {},
			size = {width: 0, height: 0};

		if (options.mode == 'vertical'){
			delete size.width;
			delete options.planes.width;
		} else if (options.mode == 'horizontal'){
			delete size.height;
			delete options.planes.height;
		}


		getStylesList(options.styles, options.planes).each(function(style){
			styles[style] = this.getStyle(style).toInt();
		}, this);

		Object.each(options.planes, function(edges, plane){

			var capitalized = plane.capitalize();
			styles[plane] = this.getStyle(plane).toInt();
			size['total' + capitalized] = styles[plane];

			edges.each(function(edge){
				var edgesize = calculateEdgeSize(edge, styles);
				size['computed' + edge.capitalize()] = edgesize;
				size['total' + capitalized] += edgesize;
			});

		}, this);

		return Object.append(size, styles);
	}

});

})();
var Slider = new Class({

	Implements: [Events, Options],

	Binds: ['clickedElement', 'draggedKnob', 'scrolledElement'],

	options: {/*
		onTick: function(intPosition){},
		onChange: function(intStep){},
		onComplete: function(strStep){},*/
		onTick: function(position){
			if (this.options.snap) position = this.toPosition(this.step);
			this.knob.setStyle(this.property, position);
		},
		initialStep: 0,
		snap: false,
		offset: 0,
		range: false,
		wheel: false,
		steps: 100,
		mode: 'horizontal'
	},

	initialize: function(element, knob, options){
		this.setOptions(options);
		this.element = document.id(element);
		this.knob = document.id(knob);
		this.previousChange = this.previousEnd = this.step = -1;
		var offset, limit = {}, modifiers = {'x': false, 'y': false};
		switch (this.options.mode){
			case 'vertical':
				this.axis = 'y';
				this.property = 'top';
				offset = 'offsetHeight';
				break;
			case 'horizontal':
				this.axis = 'x';
				this.property = 'left';
				offset = 'offsetWidth';
		}

		this.full = this.element.measure(function(){
			this.half = this.knob[offset] / 3;
			return this.element[offset] - this.knob[offset] + (this.options.offset * 2);
		}.bind(this));

		this.setRange(this.options.range);

		this.knob.setStyle('position', 'relative').setStyle(this.property, - this.options.offset);
		modifiers[this.axis] = this.property;
		limit[this.axis] = [- this.options.offset, this.full - this.options.offset];

		var dragOptions = {
			snap: 0,
			limit: limit,
			modifiers: modifiers,
			onDrag: this.draggedKnob,
			onStart: this.draggedKnob,
			onBeforeStart: (function(){
				this.isDragging = true;
			}).bind(this),
			onCancel: function(){
				this.isDragging = false;
			}.bind(this),
			onComplete: function(){
				this.isDragging = false;
				this.draggedKnob();
				this.end();
			}.bind(this)
		};
		if (this.options.snap){
			dragOptions.grid = Math.ceil(this.stepWidth);
			dragOptions.limit[this.axis][1] = this.full;
		}

		this.drag = new Drag(this.knob, dragOptions);
		this.attach();
		if (this.options.initialStep != null) this.set(this.options.initialStep)
	},

	attach: function(){
		this.element.addEvent('mousedown', this.clickedElement);
		if (this.options.wheel) this.element.addEvent('mousewheel', this.scrolledElement);
		this.drag.attach();
		return this;
	},

	detach: function(){
		this.element.removeEvent('mousedown', this.clickedElement);
		this.element.removeEvent('mousewheel', this.scrolledElement);
		this.drag.detach();
		return this;
	},

	set: function(step){
		if (!((this.range > 0) ^ (step < this.min))) step = this.min;
		if (!((this.range > 0) ^ (step > this.max))) step = this.max;

		this.step = Math.round(step);
		this.checkStep();
		this.fireEvent('tick', this.toPosition(this.step));
		this.end();
		return this;
	},

	setRange: function(range, pos){
		this.min = Array.pick([range[0], 0]);
		this.max = Array.pick([range[1], this.options.steps]);
		this.range = this.max - this.min;
		this.steps = this.options.steps || this.full;
		this.stepSize = Math.abs(this.range) / this.steps;
		this.stepWidth = this.stepSize * this.full / Math.abs(this.range);
		this.set(Array.pick([pos, this.step]).floor(this.min).max(this.max));
		return this;
	},

	clickedElement: function(event){
		if (this.isDragging || event.target == this.knob) return;

		var dir = this.range < 0 ? -1 : 1;
		var position = event.page[this.axis] - this.element.getPosition()[this.axis] - this.half;
		position = position.limit(-this.options.offset, this.full -this.options.offset);

		this.step = Math.round(this.min + dir * this.toStep(position));
		this.checkStep();
		this.fireEvent('tick', position);
		this.end();
	},

	scrolledElement: function(event){
		var mode = (this.options.mode == 'horizontal') ? (event.wheel < 0) : (event.wheel > 0);
		this.set(mode ? this.step - this.stepSize : this.step + this.stepSize);
		event.stop();
	},

	draggedKnob: function(){
		var dir = this.range < 0 ? -1 : 1;
		var position = this.drag.value.now[this.axis];
		position = position.limit(-this.options.offset, this.full -this.options.offset);
		this.step = Math.round(this.min + dir * this.toStep(position));
		this.checkStep();
	},

	checkStep: function(){
		if (this.previousChange != this.step){
			this.previousChange = this.step;
			this.fireEvent('change', this.step);
		}
	},

	end: function(){
		if (this.previousEnd !== this.step){
			this.previousEnd = this.step;
			this.fireEvent('complete', this.step + '');
		}
	},

	toStep: function(position){
		var step = (position + this.options.offset) * this.stepSize / this.full * this.steps;
		return this.options.steps ? Math.round(step -= step % this.stepSize) : step;
	},

	toPosition: function(step){
		return (this.full * Math.abs(this.min - step)) / (this.steps * this.stepSize) - this.options.offset;
	}

});



(function(window,$,undef){

"use strict";
    
var params = {
    Implements : [Options,Events]
    , options :{
        step : 60
        , scrollerHtml : '<span class="decrease"></span>'
            +'<div class="scroll"><span class="handle"></span></div>'
            +'<span class="increase"></span>'
        , mode : 'vertical'
        , margins : 0
        , wrapped : null
        , alwaysShow : false
    }
    , element : null 
    , scroller : null
    , scrollSize : 0 
    , areaSize : 0
    , position: 0
    , slider: null
    , events : null
    , generated : false
    , attached : false
    , axis : 'x'
    , dir : 'left'
    , property : 'width'
    , initialize : function initialize(elem,opts){
        this.setOptions(opts);
        this.element = $(elem);
        
        this.axis = (this.options.mode =='vertical') ? 'y' :'x';
        this.property = (this.options.mode =='vertical') ? 'height' : 'width';

        this.construct();
        this.attach();
    }
    , construct : function construct(){
        this.scroller = {};
        
        var scroller = this.scroller.element = new Element('div',{"class":'scroller',html:this.options.scrollerHtml}).addClass(this.options.mode)
            , ratio
            , $this = this
            , handleSize;
        
        this.scroller.inc         = scroller.getElement('.decrease');
        this.scroller.dec        = scroller.getElement('.increase');
        this.scroller.scroll     = scroller.getElement('.scroll');
        this.scroller.handle  = scroller.getElement('.handle');
        
        this.scrolled = (this.options.wrapped) ? this.options.wrapped : (function(){
            var html = $this.element.get('html');
            $this.element.empty();
            return new Element('div',{html:html,'class':'wrapped'}).inject($this.element);
        }());
        
        this.element.setStyle('overflow','hidden');
        
        this.element.adopt(scroller);
        
        this.areaSize =  this.element.getDimensions()[this.axis];
        this.scrollSize = this.scrolled.getDimensions()[this.axis];
        
        ratio = this.areaSize / this.scrollSize;
        
        handleSize = +this.scroller.scroll.getDimensions()[this.axis] * ratio;
        
        this.scroller.handle.setStyle(this.property,handleSize);

        if (this.areaSize >= this.scrollSize+this.options.margins && false == this.options.alwaysShow) this.hide();

        this.slider = new Slider(this.scroller.scroll,this.scroller.handle,{mode:this.options.mode, range : [0,this.scrollSize-this.areaSize/2+this.options.margins]});
        
        this.generated = true;
    }
    , attach : function attach(){
        var $this = this;
        
        if (this.attached) return;
        
        if (!this.generated) this.generate();
        
        this.events = {
            scrollUp :function scrollUp(){
                $this.slider.set($this.position - $this.options.step);
            }
            , scrollDown : function scrollDown(){
                $this.slider.set($this.position + $this.options.step);
            }
            , manageWheel : function manageWheel(e){
                e.preventDefault();
                if (e.wheel >0){
                    $this.events.scrollUp();
                }else{
                    $this.events.scrollDown();
                }    
            }
        };
        
        this.element.addEvent('mousewheel',this.events.manageWheel);
                
        this.scroller.inc.addEvent('click',this.events.scrollUp);
        this.scroller.dec.addEvent('click',this.events.scrollDown);
        
        this.slider.addEvent('change' , function(pos){
            if (pos < $this.position) $this.decrease($this.position - pos);
            else $this.increase(pos - $this.position);
        });
        
        this.attached = true;
    }
    , detach : function detach(){
        this.element.removeEvent('mousewheel',this.events.manageWheel);
        this.slider.detach();
        this.scroller.each(function(el){el.destroy();});
        this.generated = false;
        this.attached = false;
    }
    , increase : function increase(step){
        step = step || this.options.step;
        
        if (this.position + step > this.scrollSize-this.areaSize/2) this.position = this.scrollSize-this.areaSize/2;
        else this.position += step;
        
        this.scrolled.setStyle('margin-'+this.dir,-1*this.position);
        this.fireEvent('increase',[this.position]);
    }
    , decrease : function decrease(step){
        step = step || this.options.step;
        
        if (this.position-step < 0) this.position = 0;
        else this.position -=step;
        
        this.scrolled.setStyle('margin-'+this.dir,-1*this.position);
        this.fireEvent('decrease',[this.position]);
    }
    , show : function show(){
        this.scroller.element.setStyle('visibility','visible');    
    }
    , hide : function hide(){
        this.scroller.element.setStyle('visibility','hidden');    
    }
    , toElement : function toElement(){return this.element;}
}, 
ScrollerBar = this.ScrollerBar = new Class(params);

}).apply(this,[this,document.id]);

var isCtrl = false; var js=""; var css="";
    $$('input').addEvents({
            click: function(){
                $$('input.requeridoError').set("class","requerido");
        }
    });
function cargarDependencia(idDependencia){
    contenido = "contenido";
     recibeid("core/presentacion/indice_formas.php","dir=","dependencia="+idDependencia,contenido,"");
}
function buscaInformacion(capa,get,post,cargaJava){
    var urls = "core/presentacion/procesaBusqueda.php";
    var myRequest = new Request({url: urls+"?"+get, method: 'post',onSuccess: function(responseText, responseXML){
                $(capa).innerHTML = responseText;
                cargaHead(js,css);
            }});
         myRequest.send(); 
}
   function seleccionarCheck(objeto,nombre){
        $(nombre).value="";
         var objetos = $$("input.opcion_"+nombre);
         objetos.each(function(item){
              $(nombre).value+=item.value+":"+item.checked+"|";
         });
         
       
   }

function cambiaPeriodo(idPeriodo,div,baseDatos){
     var myRequestInforme = new Request({
	    url: 'core/presentacion/periodo.php',
	    method: 'post',
	    onRequest: function(){
	        div.set('html', '<div align = "center"><img src = "http://184.171.244.47/apps/framework/images/preload.gif" / ></div>');
	    },
	    onSuccess: function(responseText){
                responseText = responseText.split("<||>");
	        div.set('html', responseText[0]);
                $('estePeriodoContable').set('html',responseText[1]);
                $('fechaInicia'+baseDatos).value = responseText[2];
                $('fechaTermina'+baseDatos).value = responseText[3];
                
	    },
	    onFailure: function(){
	        div.set('html', 'Error Al procesar');
	    }
	}); 
	myRequestInforme.send('idPeriodo='+idPeriodo);
}
   
   function eliminarRegistro(tabla,llave,valorLlave,noActivaNuevo){
       if (confirm("Esta seguro que desea eliminar este registro?"))
        {
         if (noActivaNuevo==1){
             recibeid("core/negocio/RecibeSolicitud.php","llave="+llave+"&tabla="+tabla+"&accion=eliminar&valorLlave="+valorLlave,"","respuesta","","",""); 
         }else{
            recibeid("core/negocio/RecibeSolicitud.php","llave="+llave+"&tabla="+tabla+"&accion=eliminar&valorLlave="+valorLlave,"","respuesta","activaNuevo","","");
         }
        }
    }   
    
function alCargarForma(tag){  
	if ($("botonEsigma")){
       $("botonEsigma").addEvents({
          click: function(){
              $("apoyoSistema").morph({ 'display':'' });
              $$("#apoyoSistema .itemMapa .subItemMapa").addEvent('click',function(){   $("apoyoSistema").morph({ 'display':'none' }); });
          }
        });
}
 }
   
function nuevoAjax(){ 
  
  var xmlhttp=false; try { xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 	}catch(e){try{ xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");  } catch(E) { xmlhttp=false; }} if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp=new XMLHttpRequest(); } return xmlhttp; }

if(navigator.userAgent.indexOf("MSIE")>=0) navegador=0; else navegador=1; 
   
   function cargaHead(js,css,texto,ins){
        if (texto == null){ texto=0; }
	ajax1=nuevoAjax();
	ajax1.open("POST", "core/presentacion/cargaHead.php?css="+css+"&js="+js+"&texto="+texto, true);
	ajax1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax1.send(null);
	ajax1.onreadystatechange=function() 
	{ if(ajax1.readyState==4){ var resp=ajax1.responseXML; var javascript=resp.getElementsByTagName("javascript")[0].childNodes[0].data; var css=resp.getElementsByTagName("css")[0].childNodes[0].data; var etiquetaScript=document.createElement("script");
			$('headers').appendChild(etiquetaScript);                        
			etiquetaScript.text=javascript;
			var etiquetaStyle=document.createElement("style");
			$('headers').appendChild(etiquetaStyle);
			if(navegador==0){var contenidoCSS=css.split("{"); var ultimaEtiquetaStyle=document.styleSheets[document.styleSheets.length-1]; ultimaEtiquetaStyle.addRule(contenidoCSS[0], "{"+contenidoCSS[1]);}
			else{ var contenidoCSS=document.createTextNode(css); etiquetaStyle.appendChild(contenidoCSS);}
		} 	
        }
   }
   
function cargar(archivo,contenido,llave,valorLlave,jsPriv,cssPriv,esAgrupador,nombre,idProceso,ins,extra){
    if (contenido==null){  contenido = "contenido"; }
    if (esAgrupador==1){ contenido =  nombre ; 
        $$(".subItemMapa").each(function (item, index){
            item.innerHTML = "";
        
    });
}   if (esAgrupador==1){
        recibeid("core/presentacion/indice_formas.php","dir="+archivo+"&esAgrupador="+esAgrupador,"llave="+llave+"&valorLlave="+valorLlave+"&interfase=1&idProceso="+idProceso+extra,contenido,"activaGrilla",jsPriv,cssPriv,null,102);
    }else{
        recibeid("core/presentacion/indice_formas.php","dir="+archivo+"&esAgrupador="+esAgrupador,"llave="+llave+"&valorLlave="+valorLlave+"&interfase=1&idProceso="+idProceso+extra,contenido,"activaGrilla",jsPriv,cssPriv,null,101);
    }
  //  new Fx.Accordion($('registros'), '.itemMapa', '.subItemMapa');   
}

function cargarSimple(archivo,contenido,js,css){
    if (contenido==null){         contenido = "contenido";    }
    recibeid("core/presentacion/indice_formas.php","dir="+archivo,"interfase=2",contenido,"",js,css);
}

function cargaEdicion(llave,tabla,archivo,contenido,valorLlave,jsPriv,cssPriv,datoPrecargado,tag){cargar(archivo,contenido,llave,valorLlave,jsPriv,cssPriv,null,null,undefined,datoPrecargado,null,tag);  }
function cerrarSesion(){ location.href = "core/salir.php"; }
function buscar(frm,tabla,llave){ recibeid("core/negocio/startRecibeSolicitud.php","llave="+llave+"&tabla="+tabla+"&accion=buscar",variables,"respuesta",""); }
function cargaEdicionGrilla(llave,archivo,valorLlave,FEK,ValorFEK,interfasePadre,despuesDe){ carga_div("core/presentacion/indice_formas.php","Editar/Agregar Registro",800,450,"llave="+llave+"&valorLlave="+valorLlave+"&interfase=2&FEK="+FEK+"&ValorFEK="+ValorFEK+"&interfasePadre="+interfasePadre,"dir="+archivo,undefined,undefined,undefined,despuesDe);}
function nuevoRegistro(dir){ cargar(dir,"dependenciaList"); }

function enviar_formulario(modulo,archivo,frm,contenido){
    erroresValidacion = null;
    if (contenido==null || contenido== ""){
        contenido = "contenido";
    }
    var variables = "";
    for (i=0;i<frm.elements.length;i++){
        if (frm.elements[i].name.substring(0,3) == "Gu_"){
                  if((frm.elements[i].className == "requerido" || frm.elements[i].className == "requeridoError" ) && frm.elements[i].value == ""){
               frm.elements[i].className = "requeridoError"; 
               erroresValidacion = 1;
            }
            if(frm.elements[i].className == "numero" && validarEntero(frm.elements[i].value)){
               frm.elements[i].className = "requeridoError"; 
               erroresValidacion = 1;
            }
            variables+= frm.elements[i].name.substring(3)+"="+frm.elements[i].value+"&";
        }
        
    }
        if (erroresValidacion==1){
            alert("Ingrese toda la Informacion requerida");   
        }else{
            recibeid(modulo+"/"+archivo,"",variables,contenido,"");
        }    
}

function cargarBusqueda(urls,titulo,ancho,alto,post,get,ins,html){
    carga_div(urls,titulo,ancho,alto,post,get,ins,1,html);
}

function carga_div(urls,titulo,ancho,alto,post,get,ins,cargaJava,html,funcion){
    if (html==undefined){html="";}
    var contenido = $$("body")[0];
    var contenidosBox = document.createElement("div");
    with (contenidosBox){ id = "contenidos_box"+ins; className = "contenidos_box"; style.position = "fixed"; style.top = 0; style.left = 0; style.height = "100%"; style.width = "100%"; }
    var contenidosBoxTexto = document.createElement("div");
    posLeft = (screen.width-ancho)/2;
    with (contenidosBoxTexto){ id = "contenidos_box_texto"+ins; className="contenidos_box_texto"; style.position = "absolute"; style.width = ancho; style.height = alto; style.left = posLeft;style.top =  100; }
    var contenidosBoxTitulo = document.createElement("div");
    contenidosBoxTitulo.id = "contenidos_box_titulo"+ins;
    contenidosBoxTitulo.className = "contenidos_box_titulo";
    var contenidosBoxContenido = document.createElement("div");
    contenidosBoxContenido.id = "contenidos_box_contenido"+ins;
    contenidosBoxContenido.className = "contenidos_box_contenido";
    contenidosBoxTexto.appendChild(contenidosBoxTitulo);
    contenidosBoxTexto.appendChild(contenidosBoxContenido);
    contenido.appendChild(contenidosBox);
    contenido.appendChild(contenidosBoxTexto);
    $("contenidos_box_titulo"+ins).innerHTML = "<span  onmousemove = ''>"+titulo+"</span><span id = 'cerrarInterno'><a href = '#' onclick = 'cierra_box("+ins+")' >&nbsp;&nbsp;<img src = 'images/close.gif' alt = 'Cerrar' /></div></a></span><div style = 'clear:both'>";
    $("contenidos_box"+ins).morph({ 'opacity': '0.9' });
          var myRequest = new Request({url: urls+"?"+get, method: 'post',
		onSuccess: function(responseText, responseXML){
                 
                $("contenidos_box_contenido"+ins).innerHTML = html+responseText;
					Cufon.replace('.boton');
			Cufon.replace('.notas');
			Cufon.replace('.migaDependencias');
			Cufon.replace('.dependenciaItem');
			Cufon.replace('.migaDependenciasActual');
			Cufon.replace('#armar_migas spam');
                if (cargaJava==1){
                  cargaHead(js,css);
                }
                if (funcion!=undefined){ funcion.attempt(); }
            },
	    onRequest: function(){
		    $("contenidos_box_contenido"+ins).innerHTML = "<div align = 'center'><img align = 'center'  src='images/cargando.gif' ><br />Cargando</div>";
		    
	     }
	    });
        
         myRequest.post(post);
}

function cierra_box(ins){
    contenedor = $("contenidos_box"+ins); contenedor.parentNode.removeChild(contenedor); contenedor = $("contenidos_box_texto"+ins); contenedor.parentNode.removeChild(contenedor);
}

function mover_box(event,ins){ new Drag.Move($('contenidos_box_texto'+ins), {    container: $$('body') }); }

function recargaGrilla(llave,tabla,archivo,contenido,valorLlave){
	cargaEdicion(llave,tabla,archivo,contenido,valorLlave);  
	
}

function guarda_formulario(frm,tabla,tipo,llave,dir,funcion){
    var variables = "";
    var erroresValidacion = null;
    for (i=0;i<frm.elements.length;i++){
        if (frm.elements[i].name.substring(0,3) == "Gu_" || frm.elements[i].name.substring(frm.elements[i].name.length-2) == "_m" ){
              if((frm.elements[i].className == "requerido" || frm.elements[i].className == "requeridoError") && frm.elements[i].value == ""){
               frm.elements[i].className = "requeridoError"; 
               erroresValidacion = 1;
            }
            if(frm.elements[i].className == "numero" && validarEntero(frm.elements[i].value)){
               frm.elements[i].className = "requeridoError"; 
               erroresValidacion = 1;
            }
            if (frm.elements[i].name.substring(0,3) == "Gu_"){
                variables+= frm.elements[i].name.substring(3)+"="+frm.elements[i].value+"&";
            }
        }
    }
    if (erroresValidacion==1){
        alert("Algunos Campos no cumplen con los requisitos");   
        }else{
        recibeid("core/negocio/RecibeSolicitud.php","llave="+llave+"&tabla="+tabla+"&accion="+tipo,variables,"respuesta","activa_edita","","",llave,null,funcion);
    }
}

function validarEntero(valor){ valor = parseInt(valor); if (isNaN(valor)) { return 1;}else{ return 0 } } 

function objetus(file) {
    xmlhttp=false;
    this.AjaxFailedAlert = "Su navegador no soporta las funcionalidades de este sitio y podria experimentarlo de forma diferente a la que fue pensada. Por favor habilite javascript en su navegador para verlo normalmente.\n";
    this.requestFile = file;  this.encodeURIString = true; this.execute = false;
    if (window.XMLHttpRequest) { this.xmlhttp = new XMLHttpRequest(); if (this.xmlhttp.overrideMimeType) { this.xmlhttp.overrideMimeType('text/xml'); }
    } else if (window.ActiveXObject) {  try { this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }catch (e) { try { this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { this.xmlhttp = null; } }
    if (!this.xmlhttp && typeof XMLHttpRequest!='undefined') { this.xmlhttp = new XMLHttpRequest(); if (!this.xmlhttp){ this.failed = true; } }}
    return this.xmlhttp ;
}
function cambiaAtributo(nombre,atributo,valor,tipo){
    if (tipo==null)
        tipo="id";
    
    tag = null;
    switch (tipo){        case "id": tag = $(nombre); break;        case "nombre": tag = $$(nombre); break;    }
}

var vaciaMSJ = function() {
    $('respuesta').set('html','');
    $clear(vaciaMSJ.timer);
     
};

function recibeid(_pagina,valorget,valorpost,capa,despues,jsPriv,cssPriv,llave,ins,funcion){
  
     if (jsPriv || cssPriv){ css = cssPriv; js= jsPriv; }
    
    var ajax = new Array() 
	
     
    ajax[ins]=objetus(_pagina);
    if(valorpost!=""){ ajax[ins].open("POST", _pagina+"?"+valorget+"&tiempo="+new Date().getTime(),true); } 
        else { ajax[ins].open("GET", _pagina+"?"+valorget+"&tiempo="+new Date().getTime(),true); }
    $(capa).innerHTML = "<div class = 'waith'></div><div class = 'waithContenido' align='center'><img align = 'center'  src='images/cargando.gif' ><br />Cargando</div>";
    ajax[ins].onreadystatechange=function() { 
	    
    if (ajax[ins].readyState==1){ $(capa).innerHTML = "<div class = 'waith'></div><div class = 'waithContenido' align='center'><img align = 'center'  src='images/cargando.gif' ><br />Cargando</div>"; }

    if (ajax[ins].readyState==4) {
        if(ajax[ins].status==200){
                if (ajax[ins].responseText=="0"){
                    location.href = "index.php?msj=Informacion Incorrecta"
                }else if(ajax[ins].responseText=="1"){
                    location.href="index.php";
                }else{
			
                    resp = ajax[ins].responseText.split("&|&");
			
                    $(capa).innerHTML = resp[0];
			Cufon.replace('.boton');
			Cufon.replace('.notas');
			Cufon.replace('.migaDependencias');
			Cufon.replace('.dependenciaItem');
			Cufon.replace('.migaDependenciasActual');
			Cufon.replace('#armar_migas spam');
			
			
                    alCargarForma();
                    if (jsPriv!="" || cssPriv!=""){ if(ins!=102){ cargaHead(js,css,ins); }}
                    
                    
                    
                     if(capa == "respuesta"){
                        if (resp[1] == 1){
                            vaciaMSJ.timer = vaciaMSJ.periodical(1000);
                        } 
                    }
                }
            if (resp[1]==1){ ejecutar_despues(despues,resp[2],llave); }
            if (funcion!=undefined){ funcion.attempt(); }
            }else if(ajax[ins].status==404){
                capa.innerHTML = "La direccion no existe";
            }else{
                capa.innerHTML = "Error: ".ajax.status;
            }
        }
    }

    if(valorpost!=""){ ajax[ins].setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); ajax[ins].send(valorpost);} 
        else { ajax[ins].send(null);}

}
 
function ejecutar_despues(despues,tag1,tag){
    
    switch (despues){
        
        case "activa_edita":
         if($('accion').value!="editar"){
            $(tag).value=tag1;
            $(tag).name="Gu_"+$(tag).name;
            $('accion').value = 'editar';
         
         if ($('autorecarga') && $('autorecarga').value == 1){
            cargaEdicion(tag,'',$('fileConf').value,'dependenciaList',tag1);
         }
         }
         break;
         case "activaNuevo":
             $("Nuevo").onclick();
         break;
        
    }
    
}

function lista_ajax(destino,dato,tabla,mostrar,enlace,filtro,mos,base,tecla)
{
     if (tecla == 38){
        if (linea!=0){ $('line_'+linea+destino).style.backgroundColor = '#ffffff'; }
        linea--;
        $('line_'+linea+destino).style.backgroundColor = '#DBE7F6';
        $('line_'+linea+destino).onmousemove();
        return 0;
    }
    if (tecla == 13){
        $("salida_"+destino).style.display='none';
        return 0;
    }
    if (tecla == 40){
        if (linea!=0){ $('line_'+linea+destino).style.backgroundColor = '#ffffff'; }
        linea++;
        $('line_'+linea+destino).style.backgroundColor = '#DBE7F6';
         $('line_'+linea+destino).onmousemove();
        return 0;
    }
    linea=0;
	var procesador="core/presentacion/procesaControles.php";
        $("salida_"+destino).style.display = "";
    $("salida_"+destino).innerHTML = "<div align = 'center'><img src = 'images/miniEspera.gif' /></div>";
	var url="dato="+dato+"&tabla="+tabla+"&mostrar="+mostrar+"&enlace="+enlace+"&nombre="+destino+"&filtro="+filtro+"&mos="+mos+"&base="+base;
           var myRequest = new Request({url: procesador, method: 'post',onSuccess: function(responseText, responseXML){
                $("salida_"+destino).innerHTML = responseText;
            }});
         
         myRequest.get(url);
}
/*
var formulario=new Class({
    initialize: function(forma,tabla,llave,padre ){
        
    }
});
  */ 
var SortingTable = new Class({

  initialize: function( table, options ) {
    this.options = $merge({
      zebra: true,
      details: false
    }, options);
    
    this.table = $(table);
  
    this.tbody = $(this.table.getElementsByTagName('tbody')[0]);
    if (this.options.zebra) {
      SortingTable.stripe_table( this.tbody.getElements( 'tr' ) );
    }
    
    this.headers = new Hash;
    var thead = $(this.table.getElementsByTagName('thead')[0]);
    thead.className = "ordenable";
    
    $each(thead.getElementsByTagName('tr')[0].getElementsByTagName('th'), function( header, index ) {
         header.className="seleccionable" ;
      var header = $(header);
      this.headers.set( header.get('text'), { column: index } );
      header.addEvent( 'mousedown', function(evt){
        var evt = new Event(evt);
        this.sort_by_header( evt.target.get('text'),evt.target );
      }.bind( this ) );
    }.bind( this ) );

    this.load_conversions();
  },

  sort_by_header: function( header_text, objeto ){
    this.rows = new Array;
    var trs = this.tbody.getElements( 'tr' );
     var thead = $(this.table.getElementsByTagName('thead')[0]);
     
     $each(thead.getElementsByTagName('tr')[0].getElementsByTagName('th'), function( header, index ) { header.className="seleccionable" ; });     
     
    if (objeto.className != "seleccionadaAbajo"){
        objeto.className = "seleccionadaAbajo";
    }else{
        objeto.className = "seleccionadaArriba";
    }
    while ( trs.length > 0 ) {
      var row = { row: trs.shift().dispose() };
      if ( this.options.details ) {
        row.detail = trs.shift().dispose();
      }
      this.rows.unshift( row );
    }

    var header = this.headers.get( header_text );
  
    if ( this.sort_column >= 0 && this.sort_column == header.column ) {
      // They were pulled off in reverse
    } else {
      this.sort_column = header.column;
      if (header.conversion_function) {
        this.conversion_function = header.conversion_function;
      } else {
        this.conversion_function = false;
        this.rows.some(function(row){
          var to_match = $(row.row.getElementsByTagName('td')[this.sort_column]).get('text');
          //alert(to_match);
          if (to_match == ''){ return false }
          this.conversions.some(function(conversion){
            if (conversion.matcher.test( to_match )){
              this.conversion_function = conversion.conversion_function;
              return true;
            }
            return false;
          }.bind( this ));
          if (this.conversion_function){ return true; }
          return false;
        }.bind( this ));
        header.conversion_function = this.conversion_function.bind( this );
        this.headers.set( header_text, header );
      }
      this.rows.each(function(row){
        row.compare_value = this.conversion_function( row );
      }.bind( this ));
      this.rows.sort( this.compare_rows.bind( this ) );
    }

    var index = 0;
    while (this.rows.length > 0) {
      var row = this.rows.shift();
      row.row.injectInside( this.tbody );
      if (row.detail){ row.detail.injectInside( this.tbody ) };
      if ( this.options.zebra ) {
        row.row.removeClass( 'alt' );
        if (row.detail){ row.detail.removeClass( 'alt' ); }
        if ( ( index % 2 ) == 0 ) {
          row.row.addClass( 'alt' );
          if (row.detail){ row.detail.addClass( 'alt' ); }
        }
      }
      index++;
    }
    this.rows = false;
  },

  compare_rows: function( r1, r2 ) {
    if ( r1.compare_value > r2.compare_value ) { return  1 }
    if ( r1.compare_value < r2.compare_value ) { return -1 }
    return 0;
  },
  
  load_conversions: function() {
    this.conversions = $A([
      // YYYY-MM-DD, YYYY-m-d
      { matcher: /\d{4}-\d{1,2}-\d{1,2}/,
        conversion_function: function( row ) {
          var cell = $(row.row.getElementsByTagName('td')[this.sort_column]).get('text');
          var re = /(\d{4})-(\d{1,2})-(\d{1,2})/;
          cell = re.exec( cell );
          return new Date(parseInt(cell[1]), parseInt(cell[2], 10) - 1, parseInt(cell[3], 10));
        }
      },
      // Fallback 
      { matcher: /.*/,
        conversion_function: function( row ) {
          return $(row.row.getElementsByTagName('td')[this.sort_column]).get('text');
        }
      }
    ]);
  }

});

SortingTable.stripe_table = function ( tr_elements  ) {
  var counter = 0;
  $$( tr_elements ).each( function( tr ) {
    if ( tr.style.display != 'none' && !tr.hasClass('collapsed') ) {
      counter++;
    }
    tr.removeClass( 'alt' );   
    if ( !(( counter % 2 ) == 0) ) {
      tr.addClass( 'alt' );   
    }
  }.bind( this ));
}
function imprSelec(nombre)
    {
      var ficha = document.getElementById(nombre);
      var ventimp = window.open(' ', 'popimpr');
      ventimp.document.write( ficha.innerHTML );
      ventimp.document.close();
      ventimp.print( );
      //ventimp.close();
    }
    
    //window.addEvent('domready', function() {
	//	document.body.addEvent('contextmenu',function(e) {
	//		e.stop();
	//	});

	
//	});
	

var run = function(){
  var req = new XMLHttpRequest();
  req.timeout = 5000;
  req.open('GET', 'http://lineadecodigo.co', true);
  req.send();
}

setInterval(run, 3000);