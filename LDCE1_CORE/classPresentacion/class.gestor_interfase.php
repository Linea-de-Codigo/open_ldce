<?
error_reporting(E_ALL);


if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}


class gestor_interfase{

    public $archivo= null;

    public $interfase=null;


    public $variables=null;



    public $modulo=null;


    public $forma = null;

    public $javascripts = null;


    public $css=null;


    public $titulo= NULL;


    function __construct($archivo,$moduloCarpeta,$titulo,$css=NULL,$javascripts=NULL,$errores=NULL){
        
        // section 127-0-1-1-57c4604f:12fc6189999:-8000:000000000000087A begin
        global $capturador_errores;
        $menu_mapa = NULL;
        $this->archivo = $archivo;
        $this->modulo = $moduloCarpeta;
        $this->javascripts = $javascripts;
        $this->variables = new cargar_variables();
        $this->titulo = $titulo;

        $this->css  = $this->carga_css($css);
       

    }


    function carga_javascript($javascripts){
         // section 127-0-1-1-10eea897:1301d53a03d:-8000:0000000000000B4D begin
        $javascript = NULL;
        if ($javascripts){
            foreach ($javascripts as  $javas){
                $javascript.= "<script type=\"text/javascript\" src = \"$javas\"></script>";
            }
        }
        
        $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/procesador1.4.js\"></script>";
        $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/complementos.js\"></script>";
        $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/kernell.js\"></script>";
        $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/masks.js\"></script>";
        $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/calendar.js\"></script>";
	 $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/cufon.js\"></script>";
	 $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/Covered_By_Your_Grace_400.font.js\"></script>";
         $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/offline.js\"></script>";
         $javascript.= "<script type=\"text/javascript\" src = \"".$this->variables->CFrame."javascript/mootagfy.js\"></script>";



         return $javascript;

        // section 127-0-1-1-10eea897:1301d53a03d:-8000:0000000000000B4D end
    }

    /**
     * Permite establecer la forma que se va a cargar dentro del archivo de
     *
     * @access public
     * @author Dimar Borda
     * @param  forma Establece la forma que se va a cargar en la interfase.
     * @return mixed
     */
    public function establecer_forma($forma)
    {
        // section 127-0-1-1-10eea897:1301d53a03d:-8000:0000000000000B49 begin

         $this->forma = $this->modulo."/".$forma;

        // section 127-0-1-1-10eea897:1301d53a03d:-8000:0000000000000B49 end
    }


    /**
     * Retorna la Interfase
     *
     * @access public
     * @author Dimar Borda
     * @return mixed
     * @version 1.0
     */
    function retorna_interfase(){
          //section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000A84 begin
      
        $javascript = NULL;
        $texto = NULL;
        $errores = NULL;
        $titulo = $this->titulo;
        $carpeta = $this->variables->CAplicacion.$this->variables->CPlantillas;
        $javascript = $this-> carga_javascript($this->javascripts);
        $css = $this->css;

        if ($this->forma != NULL)
            $moduloCarga = $this->forma;
        else
            $moduloCarga = $this->modulo;


        try{
          $fp=fopen($carpeta."/".$this->archivo,"r");
           while ($linea= fgets($fp,1024))
            {
                eval("\$linea = \"$linea\";");
              $texto.= $linea;
            }
            $this->interfase = $texto;
        } catch (Exception $ex){
            $this->interfase = "<div><h1>".$ex."</h1></div>";
        }
            $str= $this->interfase;
            return $str;
               // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000A84 end
        }
            /**
     * Carga los css de la  plantilla.
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param  css array de css que se desean cargar
     * @return mixed
     */
    public function carga_css($css)
    {
        // section 127-0-1-1-25fdbd22:1301d8e5cb6:-8000:0000000000000B6B begin
        $salida = NULL;
        
        if ($css!=NULL)
            foreach ($css as $css_) {
                $salida.="<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href = \"".$css_."\" />";
            }

         $salida.="<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href = \"css/css.css\" />";
         $salida.="<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href = \"css/complementos.css\" />";
        // section 127-0-1-1-25fdbd22:1301d8e5cb6:-8000:0000000000000B6B end
         return $salida;
    }

}


?>