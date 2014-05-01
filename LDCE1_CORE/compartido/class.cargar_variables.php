<?
error_reporting(E_ALL);
/**
 * Clase para cargar las variables globales del archivo xml de variables
 *
 * @author RedCampó
 * @since 12 de Mayo de 2011
 * @version 1.0
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AB1-includes begin
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AB1-includes end

/* user defined constants */
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AB1-constants begin
// section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AB1-constants end

/**
 * Clase para cargar las variables globales del archivo xml de variables
 *
 * @access public
 * @author RedCampó
 * @since 12 de Mayo de 2011
 * @version 1.0
 */
class cargar_variables{
     // --- ASSOCIATIONS --
    // --- ATTRIBUTES ---
    /**
     * Carptea de Imagenes del Sistema
     *
     * @access public
     */
    public $CImagenes=null;

    /**
     * Carpeta de css del sistema
     *
     * @access public
     */
    public $CCSS=null;

    /**
     * Short description of attribute CJavascript
     *
     * @access public
     */
    public $CJavascript=null;

    /**
     * Carpeta de javascripts del sistema
     *
     * @access public
     */
    public $CPlantillas=null;

    /**
     * Carpeta del Sistema
     *
     * @access public
     */
    public $CAplicacion=null;

    /**
     * Nombre del Sistema
     *
     * @access public
     */
    public $NombreAplicacion=null;

    /**
     * Titulo para la barra de titulos del sistema
     *
     * @access public
     */
    public $TituloAplicacion=null;

    /**
     * Version del SIstema
     *
     * @access public
     */
    public $Version=null;

    /**
     * Motor para coneccion con la base de datos Principal
     *
     * @access public
     */
    public $Motor=null;

    /**
     * usuario para la conexion con la base de datos
     *
     * @access public
     */
    public $Usuario=null;

    /**
     * Clave para la conexion con la base de datos
     *
     * @access public
     */
    public $Clave=null;

        /**
     * Carpeta Donde se Encuentra el Framwework dentro de la estructura de
     *
     * @access public
     */
    public $CFrame=NULL;
    
    
    /**
     * Variable que almcena la ubicacion de la carpeta para subir archivos
     *
     * @access public
     */
    public $uploadDir = NULL;
    
    public $downloadDir = NULL;
    
    public $menus = NULL;

    // --- OPERATIONS ---

    /**
     * Clase constructora que recibe la ubicacion del xml de configuracion.
     *
     * @access public
     * @author Dimar Borda
     * @param  archivo Nombre y ubicacion del arhivo con la configuracion del sistema
     * @return mixed
     * @since 12 de Mayo de 2011
     * @version 1.0
     */
    function  __construct($archivo = NULL) {

        // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AD3 begin

	if ($archivo==NULL)	
		$archivo=__ArchivoVariables;
                

        $cargar_xml = new xml_procesador($archivo);
        $aux =  $cargar_xml->tag("imagenes",1);
        $this->CImagenes = $aux[0];

        $aux =  $cargar_xml->tag("plantillas",1);
        $this->CPlantillas = $aux[0];

        $aux =  $cargar_xml->tag("aplicacion",1);
        $this->CAplicacion = $aux[0];

         $aux =  $cargar_xml->tag("framework",1);
        $this->CFrame = $aux[0];
        
           $aux =  $cargar_xml->tag("upload",1);
        $this->uploadDir = $aux[0];
	
	$aux =  $cargar_xml->tag("download",1);
        $this->downloadDir = $aux[0];
	
	
	$aux =  $cargar_xml->tag("menus",1);
        $this->menus = $aux[0];

        $aux_motor =  $cargar_xml->tag("motor",1);
        
        $aux_usuario =  $cargar_xml->tag("usuario",1);
        
        $aux_clave =  $cargar_xml->tag("clave",1);
        
        $aux_tipo = $cargar_xml->atributos("base_datos","accion",1);

        for ($n=0;$n<3;$n++){
            $this->Motor[$aux_tipo[$n]] = $aux_motor[$n];
            $this->Usuario[$aux_tipo[$n]] = $aux_usuario[$n];
            $this->Clave[$aux_tipo[$n]] = $aux_clave[$n];
        }


         $aux =  $cargar_xml->tag("titulo",1);
        $this->TituloAplicacion = $aux[0];

         $aux =  $cargar_xml->tag("aplicacion",1);
      $this->CAplicacion = $aux[0];
      // section 127-0-1-1-25aabc25:12fda155ee7:-8000:0000000000000AD3 end
    }
}/* end of class cargar_variables */
?>
