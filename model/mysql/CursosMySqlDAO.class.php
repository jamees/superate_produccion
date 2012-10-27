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
class CursosMySqlDAO implements CursosDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     */

    public function queryBuscaCursos($userid, $rol) {
        $sql = 'SELECT C.id,C.fullname,C.shortname 
                FROM ' . ConnectionProperty::$prefijo . 'role_assignments A, ' . ConnectionProperty::$prefijo . 'course C, ' . ConnectionProperty::$prefijo . 'context X 
                WHERE A.userid = ? and A.roleid = ? and A.contextid=X.id and X.instanceid=C.id';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($userid);
        $sqlQuery->setNumber($rol);
        return $this->getList($sqlQuery);
    }
    
     public function queryBuscaCursosDirector($username) {
        $sql = 'SELECT C.id,C.fullname,C.shortname 
                FROM ' . ConnectionProperty::$prefijo . 'role_assignments A, ' . ConnectionProperty::$prefijo . 'course C, ' . ConnectionProperty::$prefijo . 'context X 
                WHERE A.userid = (SELECT id FROM ' . ConnectionProperty::$prefijo . 'user WHERE username = ? ) and A.contextid=X.id and X.instanceid=C.id';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($username);
        return $this->getList($sqlQuery);
    }

    public function queryBuscaTodoCursos() {
        $sql = 'SELECT C.id,C.fullname,C.shortname 
                FROM ' . ConnectionProperty::$prefijo . 'course C';
        $sqlQuery = new SqlQuery($sql);
        return $this->getList($sqlQuery);
    }

    /**
     * Read row
     *
     * @return PersonasMySql 
     */
    protected function readRow($row) {
        $curso = new Cursos();
        $curso->id = $row['id'];
        $curso->fullname = $row['fullname'];
        $curso->shortname = $row['shortname'];
        return $curso;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $curso = $this->readRow($tab[$i]);
            $ret[$curso->id] = $curso;
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
