<?php

/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface MantenedorDAO {
    /*
     * Retorna los datos del usuario y el rol
     * @param string $username
     */
    public function queryBuscaImagenInstitucion($institucion);
    public function queryUpdateInstitucion($nombre, $plataforma, $imagen, $campo_institucion, $institucion);
    public function queryInsertInstitucion($nombre, $plataforma, $imagen, $campo_institucion);

    /**
     * Get Domain object by primry key
     *
     * @param int $id
     * @return Persona 
     */
}

?>