<?php
class compatibilidadDB{
    public function __construct() {
        $baseDatos = NULL;
        global $globales;
           $this->baseDatos = new db($globales, __baseDatos,"editar");
           $conexion = $this->baseDatos->connect(true);
           $conexion = $this->baseDatos->db(true);
              $conexion = $this->baseDatos->setEncoding();
    }
    public function ejecutaSQL($ql,$esperaRecord = 0){
        
        return $this->baseDatos->query($ql,$esperaRecord);
        
    }
    public function arrayRegistros($tabla,$campos,$filtro=NULL){
       
        $filtroFinal = NULL;
        
        if ($filtro!=NULL)
            $filtroFinal = $filtro;
        
        $consulta = NULL;
        
       $sql = "select $campos from $tabla $filtroFinal";
        $queryresult = $this->ejecutaSQL($sql, 1);
        while($row=$this->baseDatos->fetchArray($queryresult))
        {
            $consulta[]=$row;
	}
            $this->baseDatos->freeResult($queryresult);
       return $consulta;
    }
    public function creaArray($queryresult){
            $consulta = NULL;
           while($row=$this->baseDatos->fetchArray($queryresult))
        {
            $consulta[]=$row;
	}
            $this->baseDatos->freeResult($queryresult);
       return $consulta; 
    }
    
}
?>
