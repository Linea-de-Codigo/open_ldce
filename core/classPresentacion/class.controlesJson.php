<?
class controlesJson{

private $campo,$tipo,$objeto,$consecutivoVal,$valor;	

function __construct($campo,$tipo,$objeto,$consecutivoVal,$valor){
	$this->campo = $campo;
	$this->tipo = $tipo;
	$this->objeto =  $objeto;
	$this->consecutivoVal = $consecutivoVal;
	$this->valor=$valor;
	
}
public function escribeCampo(){
    switch ($this->tipo) {
        case "campo_texto":
            return controles::campo_texto($this->campo,$this->valor,$this->objeto->tamano,"","Obj".$this->consecutivoVal."_");
            break;
        case "campo_fecha":
            return controles::campo_fecha($this->campo,$this->valor,"Obj".$this->consecutivoVal."_");
            break;
        case "campo_memo":
            return controles::campo_memo($this->campo,$this->valor,$this->objeto->columnas,$this->objeto->filas,"Obj".$this->consecutivoVal."_");
            break;
        case "combo_rapido":
        
        return controles::combo_rapido($this->campo,$this->objeto->datos,$this->valor,NULL,"Obj".$this->consecutivoVal."_");
                break;
       case "lista_ajax":
           return controles::lista_ajax($this->objeto->tabla,$this->objeto->mostrar,$this->objeto->enlace,$this->campo,$this->objeto->tamano,$this->objeto->filtro,$this->valor,"",NULL,"Obj".$this->consecutivoVal."_","");
           break;
        default:
        return "Tipo de campo no valido";
            break;
    }
}
}
