<?php

/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface IntentosDAO {
    /*
     * Retorna los datos del usuario y el rol
     * @param string $username
     */

    public function queryBuscaIntentosRealTime();

    public function queryBuscaIntentos($idquiz, $iduser);

    /**
     * Get Domain object by primry key
     *
     * @param int $id
     * @return Persona 
     */
}

?>