<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CursosMySqlDAO
 *
 * @author JorgePaz
 */
//put your code here
/* * SELECT C.id,C.fullname,C.shortname 
 * FROM prefix_role_assignments A, prefix_course C, prefix_context X 
 *  WHERE A.userid=19952 and A.contextid=X.id and X.instanceid=C.id
 */
class AdopcionMySqlDAO implements AdopcionDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     *

    /*
     *     var nombres;
      var apellidos;
      var usuario;
      var userid;
      var nota;

     */

    public function queryBuscaAdopcion($institucion, $campo_institucion, $rol) {
        $sql = 'SELECT (n.Usuarios/d.Usuarios)*100 AS Porcentaje, d.Usuarios AS NumeroAlumnosInstitucion FROM
                (SELECT COUNT(L.id) Usuarios
                FROM ' . ConnectionProperty::$prefijo . 'role_assignments A, ' . ConnectionProperty::$prefijo . 'context X, ' . ConnectionProperty::$prefijo . 'user_lastaccess L, ' . ConnectionProperty::$prefijo . 'user U, ' . ConnectionProperty::$prefijo . 'course C
                WHERE L.courseid=C.id and 
                C.id=X.instanceid and 
                A.contextid=X.id and 
                A.userid=L.userid and
                U.deleted = 0 and
                L.userid = U.id and 
                U.' . $campo_institucion . '= ? and 
                A.roleid=?)
                AS n,
                (SELECT COUNT(A.id) Usuarios
                FROM ' . ConnectionProperty::$prefijo . 'role_assignments A, ' . ConnectionProperty::$prefijo . 'course C, ' . ConnectionProperty::$prefijo . 'user U, ' . ConnectionProperty::$prefijo . 'context X
                WHERE A.userid = U.id and
                A.roleid=? and
                U.deleted = 0 and
                U.' . $campo_institucion . '= ? and 
                A.contextid=X.id and
                X.instanceid=C.id)
                AS d';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($institucion);
        $sqlQuery->setNumber($rol);
        $sqlQuery->setNumber($rol);
        $sqlQuery->setString($institucion);
        return $this->getRow($sqlQuery);
    }

    public function queryBuscaAdopcionSemanal($institucion, $campo_institucion, $idcurso) {
        $sql = 'select count(distinct(l.userid)) as UsuariosIngresados, u.' . $campo_institucion . ', year(from_unixtime(l.time)) as ano, week(from_unixtime(l.time)) as semana
                from ' . ConnectionProperty::$prefijo . 'log l
                join ' . ConnectionProperty::$prefijo . 'user u on u.id = l.userid
                where l.course = ? and u.' . $campo_institucion . ' = ?
                group by ano, semana, course, ' . $campo_institucion . '
                order by ano desc, semana desc limit 24;';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idcurso);
        $sqlQuery->setString($institucion);
        return $this->getListAdopcionSemanal($sqlQuery);
    }

    
    /**
     * Read row
     *
     * @return PersonasMySql 
     */
      protected function readRowAdopcionSemanal($row) {
        $adopcion = new Adopcion();
        $adopcion->ano = $row['ano'];
        $adopcion->semana = $row['semana'];
        $adopcion->totalUsuariosInstitucion = $row['UsuariosIngresados'];
        return $adopcion;
    }
    
    protected function readRow($row) {
        $adopcion = new Adopcion();
        $adopcion->porcentaje = $row['Porcentaje'];
        $adopcion->totalUsuariosInstitucion = $row['NumeroAlumnosInstitucion'];
        return $adopcion;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $adopcion = $this->readRow($tab[$i]);
            $ret[$i] = $adopcion;
        }
        return $ret;
    }
    
    protected function getListAdopcionSemanal($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $adopcion = $this->readRowAdopcionSemanal($tab[$i]);
            $ret[$i] = $adopcion;
        }
        return $ret;
    }

    /**
     * Get row
     *
     * @return PersonasMySql 
     */
    protected function getRow($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Execute sql query
     */
    protected function execute($sqlQuery) {
        return QueryExecutor::execute($sqlQuery);
    }

    /**
     * Execute sql query
     */
    protected function executeUpdate($sqlQuery) {
        return QueryExecutor::executeUpdate($sqlQuery);
    }

    /**
     * Query for one row and one column
     */
    protected function querySingleResult($sqlQuery) {
        return QueryExecutor::queryForString($sqlQuery);
    }

    /**
     * Insert row to table
     */
    protected function executeInsert($sqlQuery) {
        return QueryExecutor::executeInsert($sqlQuery);
    }

}

?>
