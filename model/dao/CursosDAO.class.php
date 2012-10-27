<?php

/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface CursosDAO {
    /*
     * Retorna los datos del usuario y el rol
     * @param string $username
     */

    public function queryBuscaCursos($userid, $rol);

    public function queryBuscaCursosDirector($username);

    public function queryBuscaTodoCursos();

    /**
     * Get Domain object by primry key
     *
     * @param int $id
     * @return Persona 
     */
}

?>