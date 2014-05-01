<?php
error_reporting(E_ALL);
/**
 * Clase que lee las etiquetas y los atributos de lo archivos XML
 *
 * @author RedCampo
 * @since 12 De Mayo de 2011
 * @version 1
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AD7-includes begin
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AD7-includes end

/* user defined constants */
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AD7-constants begin
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AD7-constants end

/**
 * Clase que lee las etiquetas y los atributos de lo archivos XML
 *
 * @access public
 * @author RedCampo
 * @since 12 De Mayo de 2011
 * @version 1
 */
class gestion_error{

    public $erores;
    function  __construct() {
        $this->erores = 0;
    }
    function carga_eror($error,$faq){
        $this->erores.= "<div>$error - <a href = \"?faq=$faq\">Ver Ayuda del error</a></div>";
    }
    function construir_mensaje(){
        $salda = "<div>$this->erores</div>";
    }
    
}


class xml_procesador{
      // --- ASSOCIATIONS ---

    // --- ATTRIBUTES ---

    /**
     * Variable que almacena el Objeto XMLDocument
     *
     * @access public
     */
    public $Xml=NULL;

    /**
     * Variable que almacena la Ruta del Archivo xml a procesar
     *
     * @access public
     */
    public $archivo=NULL;

    
    private $lenguaje=NULL;
    // --- OPERATIONS ---

    /**
     * Variable Constructora del Objeto que recibe la ruta del archivo xml
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  archivo Ruta del Archivo XML a procesar
     * @return mixed
     */
   
     
    function  __construct($archivo,$lenguaje=NULL) {
      // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000ADE begin
     if ($lenguaje!=NULL){
        $this->lenguaje=new DOMDocument;
        $this->lenguaje->preserveWhiteSpace = false;
        if (file_exists($lenguaje)){
            $this->lenguaje->load($lenguaje);
        }else{
            echo "No existe el archivo de Lenguaje";
        }
     }
        $this->Xml = new domdocument;
        $this->Xml->preserveWhiteSpace = false;
        $this->archivo = $archivo;
       if (file_exists($this->archivo)){
         //  echo $this->archivo."<br />";
            $this->Xml->load($this->archivo);
       }else{
           echo 'Eror al leer el archivo '.$archivo;
    }
     // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000ADE end
    }
    /**
     * Debuelve la informacion contenida en uan etiqueta del XML
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  tag Nombre de la Etiqueta que se va a procesar
     * @param  tipo Tipo de dato que se va a debolver: 0 Priemr elemento del Array se usa cuando solo ahy una etiqueta con ese nombre, 1: Todos los elementos del array se usa cuando hay varias etiquetas con el mismo nombre
     * @return mixed
     */
    function tag($tag,$tipo=0){
           // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AEC begin
           $variable = null; $auxLenguaje=NULL;$auxLenguajeItem=NULL;
         $varS_ = $this->Xml->getElementsByTagname($tag);
         foreach ($varS_ as $var_) {
             $val = explode("$", $var_->firstChild->nodeValue);
           if (isset ($val[1]) && $this->lenguaje!=NULL){
               $auxLenguaje=$this->lenguaje->getElementsByTagname($val[1]);
               $auxLenguajeItem= $auxLenguaje->item(0);
               $variable[] = $auxLenguajeItem->firstChild->nodeValue;
           }else{
                $variable[] = $var_->firstChild->nodeValue;
           }
           }
           
         if ($tipo==0)
             return $variable[0];
         else
             return $variable;
         // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AEC end
    }
     /**
     * Debuelve el valor del aatributo de una etiqueta xml
     *
     * @access public
     * @author Diamar Borda
     * @param  tag Nombre de la Etiqueta donde esta el Atributo
     * @param  tipo Tipo de dato que se va a debolver: 0 Priemer elemento del Array se usa cuando solo ahy una etiqueta con ese nombre, 1: Todos los elementos del array se usa cuando hay varias etiquetas con el mismo nombre
     * @param  attib Nombre del atributo que se desea leer.
     * @return mixed
     * @since 12 Mayo de 2011
     * @version 1.0
     */
    function atributos($tag,$atrib,$tipo=0){
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AF0 begin
 $variable = null;
        $temas = $this->Xml->getElementsByTagname($tag);
        foreach ($temas as $tema) {
            $variable[] = $tema->getAttribute($atrib);
        }
        if ($tipo==0)
            return $variable[0];
        else
            return $variable;

   // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AF0 end
    }
}/* end of class xml_procesador */

?>