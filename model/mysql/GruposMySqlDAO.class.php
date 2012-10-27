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
class GruposMySqlDAO implements GrupoDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     */
    public function queryBuscaGrupo($userid, $idcurso) {
        $sql = 'SELECT G.name AS nombre, G.id AS id, G.courseid AS idCurso
                FROM ' . ConnectionProperty::$prefijo . 'user U
                JOIN ' . ConnectionProperty::$prefijo . 'groups_members AS M ON M.userid = U.id
                JOIN ' . ConnectionProperty::$prefijo . 'groups AS G ON  G.id = M.groupid
                WHERE U.id = ? AND G.courseid = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($userid);
        $sqlQuery->setNumber($idcurso);
        return $this->getList($sqlQuery);
    }
    
    public function queryBuscaGrupoDirector($username, $idcurso) {
        $sql = 'SELECT G.name AS nombre, G.id AS id, G.courseid AS idCurso
                FROM ' . ConnectionProperty::$prefijo . 'user U
                JOIN ' . ConnectionProperty::$prefijo . 'groups_members AS M ON M.userid = U.id
                JOIN ' . ConnectionProperty::$prefijo . 'groups AS G ON  G.id = M.groupid
                WHERE U.username = ? AND G.courseid = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($username);
        $sqlQuery->setNumber($idcurso);
        return $this->getList($sqlQuery);
    }


    /**
     * Read row
     *
     * @return PersonasMySql 
     */
    protected function readRow($row) {
        $grupo = new Grupos();
        $grupo->id = $row['id'];
        $grupo->nombre = $row['nombre'];
        $grupo->idCurso = $row['idCurso'];
        return $grupo;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $grupo = $this->readRow($tab[$i]);
            $ret[$grupo->id] = $grupo;
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
