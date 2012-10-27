<?php
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface QuizDAO{
	
    
    
    
    
    
    
        /*
         * Retorna los datos de los quizes del usuario en un deetrminado curso
         * @param int $userid
         * @param int $idcurso 
         */
        public function queryBuscaQuiz($idcurso,$userid);
        public function queryBuscaTodoQuiz($idcurso);


}
?>