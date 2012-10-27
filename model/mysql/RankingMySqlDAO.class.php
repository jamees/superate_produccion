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
    /**SELECT C.id,C.fullname,C.shortname 
       * FROM prefix_role_assignments A, prefix_course C, prefix_context X 
      *  WHERE A.userid=19952 and A.contextid=X.id and X.instanceid=C.id
     */
class RankingMySqlDAO implements RankingDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     */

    public function queryBuscaRanking($idcurso,$idquiz,$idgrupo) {
        $sql = 'SELECT U.firstname AS nombres,U.lastname AS apellidos,U.username AS usuario, U.id AS userid,QG.grade AS nota
        FROM '.ConnectionProperty::$prefijo.'user U, '.ConnectionProperty::$prefijo.'quiz Q, '.ConnectionProperty::$prefijo.'quiz_grades QG, '.ConnectionProperty::$prefijo.'groups_members M, '.ConnectionProperty::$prefijo.'groups G
        WHERE U.id=QG.userid and QG.quiz=Q.id and Q.course = ? and Q.id = ? and M.userid = U.id and G.id = M.groupid and G.courseid = ? and G.id = ? 
        ORDER BY  QG.grade DESC';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idcurso);
        $sqlQuery->setNumber($idquiz); 
        $sqlQuery->setNumber($idcurso);
        $sqlQuery->setNumber($idgrupo);   
        return $this->getList($sqlQuery);
    }

    public function queryBuscaPromedioNotasGrupo($idcurso,$idgrupo) {
       $sql = 'SELECT avg(QG.grade)
       FROM '.ConnectionProperty::$prefijo.'user U, '.ConnectionProperty::$prefijo.'quiz Q, '.ConnectionProperty::$prefijo.'quiz_grades QG, '.ConnectionProperty::$prefijo.'groups_members M, '.ConnectionProperty::$prefijo.'groups G
       WHERE U.id=QG.userid and QG.quiz=Q.id and Q.course = ? and M.userid = U.id and G.id = M.groupid and G.courseid = ? and G.id = ? ';
       $sqlQuery = new SqlQuery($sql);
       $sqlQuery->setNumber($idcurso);
       $sqlQuery->setNumber($idcurso);
       $sqlQuery->setNumber($idgrupo);   
       return $this->querySingleResult($sqlQuery);
   }
    /**
     * Read row
     *
     * @return PersonasMySql 
     */
    protected function readRow($row) {
        $ranking = new Ranking();
        $ranking->nombres = $row['nombres'];
        $ranking->apellidos = $row['apellidos'];        
        $ranking->usuario = $row['usuario'];       
        $ranking->userid = $row['userid'];        
        $ranking->nota = $row['nota'];   
        return $ranking;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $ranking = $this->readRow($tab[$i]);
            $ret[$ranking->userid] = $ranking;
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
