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
class PreguntasMySqlDAO implements PreguntasDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     */
    public function queryBuscaPreguntasQuiz($quizid) {
        $sql = 'SELECT qa.slot AS numeropregunta, qa.rightanswer AS correcto, qa.responsesummary AS respuestaAlumno, q.qtype as tipopregunta 
                FROM ' . ConnectionProperty::$prefijo . 'quiz_attempts quiza
                INNER JOIN ' . ConnectionProperty::$prefijo . 'question_usages qu ON qu.id = quiza.uniqueid
                INNER JOIN ' . ConnectionProperty::$prefijo . 'question_attempts qa ON qa.questionusageid = qu.id
                INNER JOIN ' . ConnectionProperty::$prefijo . 'question q ON qa.questionid = q.id
                INNER JOIN ' . ConnectionProperty::$prefijo . 'question_attempt_steps qas ON qas.questionattemptid = qa.id
                LEFT JOIN ' . ConnectionProperty::$prefijo . 'question_attempt_step_data qasd ON qasd.attemptstepid = qas.id
                WHERE quiza.id
                IN(
                SELECT id
                FROM ' . ConnectionProperty::$prefijo . 'quiz_attempts 
                WHERE quiz = ?
                )
                AND qasd.name =  '."'-finish'".' AND qa.maxmark > 0
                group by qa.slot';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($quizid);
        return $this->getList($sqlQuery);
    }

    /**
     * Read row
     *
     * @return PersonasMySql 
     * numeropregunta;
      correcto;
      respuestaAlumno;
      tipopregunta;
     */
    protected function readRow($row) {
        $preguntas_quiz = new Preguntas();
        $preguntas_quiz->numeropregunta = $row['numeropregunta'];
        $preguntas_quiz->correcto = $row['correcto'];
        $preguntas_quiz->respuestaAlumno = $row['respuestaAlumno'];
        $preguntas_quiz->tipopregunta = $row['tipopregunta'];
        return $preguntas_quiz;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $preguntas_quiz = $this->readRow($tab[$i]);
            $ret[$i] = $preguntas_quiz;
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
