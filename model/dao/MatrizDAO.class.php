<?php
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface MatrizDAO{
	
    
    
    
    
    
    
        /*
         * Retorna los datos del usuario y el rol
         * @param string $username
         */
        public function queryBuscaMatriz($idquiz);
        public function queryInsertMatriz($idquiz,$npregunta,$contenido,$eje,$link_repaso,$tipopregunta,$idquiz_diagnostico);
        public function queryUpdateMatriz($idquiz,$npregunta,$contenido,$eje,$link_repaso,$idquiz_diagnostico);
        public function queryDeleteMatriz($idquiz);

	/**
	 * Get Domain object by primry key
	 *
	 * @param int $id
	 * @return Persona 
	 */



}
?>