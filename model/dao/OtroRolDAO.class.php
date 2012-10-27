<?php

/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
interface OtroRolDAO {
    /*
     * Retorna los datos del usuario y el rol
     * @param string $username
     */

    public function queryBuscaPersonaOtroRolLocal($username, $password);

    public function queryBuscaPersonaOtroRolPlataforma($campo);

    public function queryBuscaPersonaOtroRolTodos();

    public function queryInsertOtroRol($nombre_rol, $username, $institucion, $nombres, $apellidos, $campo_institucion, $password);

    public function queryUpdateOtroRol($nombre_rol, $username, $institucion, $nombres, $apellidos, $campo_institucion, $password, $id);

    /**
     * Get Domain object by primry key
     *
     * @param int $id
     * @return Persona 
     */
}

?>