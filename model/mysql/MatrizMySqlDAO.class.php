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
class MatrizMySqlDAO implements MatrizDAO {


    public function queryBuscaMatriz($idquiz) {
        $sql = 'SELECT id,idquiz,n_pregunta,contenido,eje,link_repaso,tipopregunta,idquiz_diagnostico
                FROM matriz
                WHERE idquiz = ? ORDER BY CAST(n_pregunta AS UNSIGNED)';

        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idquiz);
        return $this->getList($sqlQuery);
    }

    public function queryInsertMatriz($idquiz, $npregunta, $contenido, $eje, $link_repaso,$tipopregunta,$idquiz_diagnostico) {
        $sql = 'INSERT INTO matriz (idquiz,n_pregunta,contenido,eje,link_repaso,tipopregunta,idquiz_diagnostico) VALUES (?,?,?,?,?,?,?)';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idquiz);
        $sqlQuery->setString($npregunta);
        $sqlQuery->setString($contenido);
        $sqlQuery->setString($eje);
        $sqlQuery->setString($link_repaso);
        $sqlQuery->setString($tipopregunta);
        $sqlQuery->setNumber($idquiz_diagnostico); 
        $id = $this->executeInsert($sqlQuery);
        return $id;
    }
    
    public function queryUpdateMatriz($idquiz, $npregunta, $contenido, $eje, $link_repaso, $idquiz_diagnostico) {
        $sql = 'UPDATE matriz SET contenido = ?, eje = ?, link_repaso = ?, idquiz_diagnostico = ? WHERE idquiz = ? AND  n_pregunta = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setString($contenido);
        $sqlQuery->setString($eje);
        $sqlQuery->setString($link_repaso);
        $sqlQuery->setNumber($idquiz_diagnostico); 
        $sqlQuery->setNumber($idquiz);
        $sqlQuery->setNumber($npregunta);        
        return $this->executeUpdate($sqlQuery);
    }   
    
    public function queryDeleteMatriz($idquiz) {
        $sql = 'DELETE FROM matriz WHERE idquiz = ?';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idquiz);
        return $this->executeUpdate($sqlQuery);
    }      

    protected function readRow($row) {
        $matrix = new Matriz();
        $matrix->id = $row['id'];
        $matrix->idquiz = $row['idquiz'];
        $matrix->n_pregunta = $row['n_pregunta'];
        $matrix->contenido = $row['contenido'];
        $matrix->eje = $row['eje'];
        $matrix->link_repaso = $row['link_repaso'];
        $matrix->tipopregunta = $row['tipopregunta'];
        $matrix->idquiz_diagnostico = $row['idquiz_diagnostico'];
        return $matrix;
    }

    protected function getList($sqlQuery) {
        //$pasa.= $sqlQuery;
        $tab = QueryExecutorLocal::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $matriz = $this->readRow($tab[$i]);
            $ret[$i] = $matriz;
        }
        return $ret;
    }

    /**
     * Get row
     *
     * @return PersonasMySql 
     */
    protected function getRow($sqlQuery) {
        $tab = QueryExecutorLocal::execute($sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }

    /**
     * Execute sql query
     */
    protected function execute($sqlQuery) {
        return QueryExecutorLocal::execute($sqlQuery);
    }

    /**
     * Execute sql query
     */
    protected function executeUpdate($sqlQuery) {
        return QueryExecutorLocal::executeUpdate($sqlQuery);
    }

    /**
     * Query for one row and one column
     */
    protected function querySingleResult($sqlQuery) {
        return QueryExecutorLocal::queryForString($sqlQuery);
    }

    /**
     * Insert row to table
     */
    protected function executeInsert($sqlQuery) {
        return QueryExecutorLocal::executeInsert($sqlQuery);
    }

}

?>
