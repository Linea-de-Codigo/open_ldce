<?php
class comprobarUsuario{
    
    private $conecBaseDatos;
    private $administrarTabla;

    public function __construct($objetoBaseDatos,$objetoTabla) {
        $this->conecBaseDatos = $objetoBaseDatos;
        $conexion =  $this->conecBaseDatos->connect(true);
        $conexion = $this->conecBaseDatos->db(true);
        $this->administrarTabla = $objetoTabla;
    }
    private function buscaUsuario($usuario,$contrasena,$real=NULL){
		
	
        $camposB = '`idUsuario`,`nombreUsuario`,`idPerfil`,`idEmpleado`,`idDependencia`,`contrasena`';
        
        $filtroPrevio =array("nombreUsuario"=>$usuario);
        $buscarUsuarioPrevio = $this->administrarTabla->consultarDatos($camposB,$filtroPrevio,"","","","", $this->conecBaseDatos);
        
        if (isset($buscarUsuarioPrevio[0]['contrasena'])){
			$auxPass = explode(":",$buscarUsuarioPrevio[0]['contrasena']);
			if (isset($auxPass[1])){
				$auxCont = md5($real.$auxPass[1]);
				$contrasena = $auxCont.":".$auxPass[1];
			}
		}else{ 
                    $filtroPrevio =array("nombreUsuario"=>$usuario,"contrasena"=>$contrasena);
                    
                    $buscarUsuarioPrevio = $this->administrarTabla->consultarDatos($camposB,$filtroPrevio,"","","","", $this->conecBaseDatos);
                    return $buscarUsuarioPrevio;
                }
        
        $filtro =array("nombreUsuario"=>$usuario,"contrasena"=>$contrasena);
        $buscarUsuario = $this->administrarTabla->consultarDatos($camposB,$filtro,"","","","", $this->conecBaseDatos);
        return $buscarUsuario;
    }
        private function infoEmpresa($baseDatos){
        
        $queryresult = $this->conecBaseDatos->query("select * from   001_empresa",1);
        $consulta = NULL;
        while($row=$this->conecBaseDatos->fetchArray($queryresult))
    {
            $consulta[$row['nombre_parametro']]=$row['valor_parametro'];
    }
        $_SESSION['empresa'.$baseDatos] = $consulta;
        $queryresult = $this->conecBaseDatos->query("select * from   0003_periodo where predeterminado = 1",1);
        $_SESSION['periodo'.$baseDatos] = $this->conecBaseDatos->fetchArray($queryresult);
        
    }
    private function declararSesiones($sesiones,$registros,$baseDatos){
         $this->infoEmpresa($baseDatos);
        foreach ($sesiones as $key => $value) {
           //echo $key.$baseDatos."-";
	   $_SESSION[$key.$baseDatos] = $registros[$value];
        }
    }
    public function retornaValidacion($usuario,$contrasena,$arraySesiones,$baseDatos){
        $existeUsuario = $this->buscaUsuario($usuario, md5($contrasena),$contrasena);
        if ($existeUsuario[0]['nombreUsuario']){
            $this->declararSesiones($arraySesiones,$existeUsuario[0],$baseDatos);
            return 1;
        }else{
            return 0;
        }
        
    }
}
?>
