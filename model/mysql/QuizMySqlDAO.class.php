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
class QuizMySqlDAO implements QuizDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     */
    public function queryBuscaQuiz($idcurso, $userid) {
        $sql = "SELECT Q.course AS idCurso, Q.name AS NombreQuiz, Q.id AS idQuiz
                FROM " . ConnectionProperty::$prefijo . "quiz Q, " . ConnectionProperty::$prefijo . "quiz_attempts QA
                WHERE Q.course = ? AND QA.quiz = Q.id AND QA.userid = ? AND QA.timefinish > 0 AND (Q.name like '%control%' OR Q.name like '%tarea%' OR Q.name like '%diagn%' OR Q.name like '%prueba%')
                    GROUP BY NombreQuiz, idQuiz";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idcurso);
        $sqlQuery->setNumber($userid);
        //echo $sql."<br>".$idcurso." - ".$userid;
        return $this->getList($sqlQuery);
    }

    public function queryBuscaTodoQuiz($idcurso) {
        $sql = "SELECT Q.course AS idCurso, Q.name AS NombreQuiz, Q.id AS idQuiz
                FROM " . ConnectionProperty::$prefijo . "quiz Q 
                WHERE Q.course = ? AND (Q.name like '%control%' OR Q.name like '%tarea%' OR Q.name like '%diagn%' OR Q.name like '%prueba%')  GROUP BY NombreQuiz, idQuiz";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idcurso);
        return $this->getList($sqlQuery);
    }

    public function queryEsDiagnostico($idEvaluacion) {
        $sql = "SELECT id FROM " . ConnectionProperty::$prefijo . "quiz WHERE id = ? AND name like '%diagn%'";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idEvaluacion);
        return $this->querySingleResult($sqlQuery);
    }

    public function queryBuscaNotaQuiz($idEvaluacion,$userid) {
        $sql = "SELECT grade FROM " . ConnectionProperty::$prefijo . "quiz_grades WHERE quiz = ? AND userid = ?";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idEvaluacion);
        $sqlQuery->setNumber($userid);
        return $this->querySingleResult($sqlQuery);
    }

    /*
     *    public function queryBuscaQuiz($idcurso, $userid) {
      $sql = "SELECT Q.course AS idCurso, Q.name AS NombreQuiz, Q.id AS idQuiz
      FROM " . ConnectionProperty::$prefijo . "quiz Q, " . ConnectionProperty::$prefijo . "quiz_attempts QA
      WHERE Q.course = ? AND QA.quiz = Q.id AND QA.userid = ? AND QA.timefinish > 0 AND Q.name like '%control%' OR Q.name like '%tarea%'
      GROUP BY NombreQuiz, idQuiz";
      $sqlQuery = new SqlQuery($sql);
      $sqlQuery->setNumber($idcurso);
      $sqlQuery->setNumber($userid);
      //echo $sql."<br>".$idcurso." - ".$userid;
      return $this->getList($sqlQuery);
      }


      public function queryBuscaTodoQuiz($idcurso) {
      $sql = "SELECT Q.course AS idCurso, Q.name AS NombreQuiz, Q.id AS idQuiz
      FROM " . ConnectionProperty::$prefijo . "quiz Q
      WHERE Q.course = ? AND Q.name like '%control%' OR Q.name like '%tarea%'  GROUP BY NombreQuiz, idQuiz";
      $sqlQuery = new SqlQuery($sql);
      $sqlQuery->setNumber($idcurso);
      return $this->getList($sqlQuery);
      }/
      /**
     * Read row
     *
     * @return PersonasMySql 
     */

    protected function readRow($row) {
        $quiz = new Quiz();
        $quiz->idCurso = $row['idCurso'];
        $quiz->id = $row['idQuiz'];
        $quiz->NombreQuiz = $row['NombreQuiz'];
        return $quiz;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $quiz = $this->readRow($tab[$i]);
            $ret[$quiz->id] = $quiz;
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
