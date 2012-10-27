<?php
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface GrupoDAO{
	
    
    
    
    
    
    
        /*
         * Retorna los datos del usuario y el rol
         * @param string $username
         */
        public function queryBuscaGrupo($userid,$idcurso);
        public function queryBuscaGrupoDirector($username, $idcurso);
	/**
	 * Get Domain object by primry key
	 *
	 * @param int $id
	 * @return Persona 
	 */



}
?>