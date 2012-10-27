<?php

/**
 * Class that operate on table 'personas'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2012-01-18 16:29
 */
class PersonasMySqlDAO implements PersonasDAO {

                

    public function queryBuscaPersona($username) {
        $sql = 'SELECT U.id AS id,U.username AS usuario,U.firstname AS nombre,U.lastname AS apellido,U.institution AS institucion, max(A.roleid) AS rolMoodle
        FROM '.ConnectionProperty::$prefijo.'user U, '.ConnectionProperty::$prefijo.'role_assignments A 
        WHERE A.userid=U.id AND U.username = ? group by U.id';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($username);
        return $this->getRow($sqlQuery);
    }

     public function queryBuscaAlumnosGrupo($idgrupo, $idcurso) {
        $sql = 'SELECT distinct(u.id) AS id,u.username AS usuario,u.firstname AS nombre,u.lastname AS apellido,u.institution AS institucion, a.roleid AS rolMoodle 
            FROM '.ConnectionProperty::$prefijo.'groups_members gm, '.ConnectionProperty::$prefijo.'user u, '.ConnectionProperty::$prefijo.'role_assignments a, '.ConnectionProperty::$prefijo.'course c, '.ConnectionProperty::$prefijo.'groups g 
            WHERE gm.groupid = g.id and u.id = gm.userid and gm.userid = a.userid and a.roleid = 5 and g.id = ? and g.courseid = c.id and c.id = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idgrupo);
        $sqlQuery->setNumber($idcurso);
        return $this->getList($sqlQuery);
    }
    /**
     * Read row
     *
     * @return PersonasMySql 
     */
    protected function readRow($row) {
        $persona = new Persona();
        $persona->id = $row['id'];
        $persona->usuario = $row['usuario'];        
        $persona->nombre = $row['nombre'];
        $persona->apellido = $row['apellido'];
        $persona->institucion = $row['institucion'];
        $persona->rolMoodle = $row['rolMoodle'];        
        return $persona;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $persona = $this->readRow($tab[$i]);
            $ret[$persona->id] = $persona;
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