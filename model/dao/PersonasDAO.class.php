<?php

/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface PersonasDAO {
    /*
     * Retorna los datos del usuario y el rol
     * @param string $username
     */

    public function queryBuscaPersona($username);

    /**
     * Get Domain object by primry key
     *
     * @param int $id
     * @return Persona 
     */
    public function queryBuscaAlumnosGrupo($idgrupo, $idcurso);
}

?>