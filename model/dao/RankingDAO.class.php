<?php

/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface RankingDAO {
    /*
     * Retorna los datos del usuario y el rol
     * @param string $username
     */

    public function queryBuscaRanking($idcurso, $idquiz, $idgrupo);

    public function queryBuscaPromedioNotasGrupo($idcurso, $idgrupo);
    /**
     * Get Domain object by primry key
     *
     * @param int $id
     * @return Persona 
     */
}

?>