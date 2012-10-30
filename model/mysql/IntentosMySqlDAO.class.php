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
class IntentosMySqlDAO implements IntentosDAO {

    /**
     * Retorna un usuario que coincide en nombre y apellido
     * TODO: chequear en curso
     * 
     * @author cgajardo.
     * @param string $nombre
     * @param string $apellido
     and us.id = 891 */
    public function queryBuscaIntentosRealTime() {
        $sql = 'SELECT distinct quiza.attempt AS intento, 
                quiza.sumgrades AS notaFinalQuiz, 
                qa.slot AS numeroPregunta, 
                q.qtype as tipoPregunta,
                qa.maxmark AS puntajeMaximo, 
                qas.state AS estado, 
                qas.fraction AS puntajeObtenido, 
                qa.rightanswer AS respCorrecta, 
                qa.responsesummary AS respuesta, 
                us.username AS username, 
                us.firstname AS nombre,
                us.lastname AS apellido
                FROM ' . ConnectionProperty::$prefijo . 'user us
                JOIN ' . ConnectionProperty::$prefijo . 'quiz_attempts quiza ON us.id = quiza.userid and quiza.quiz = 31 and us.username in ("13347412", "18237269") 
                JOIN ' . ConnectionProperty::$prefijo . 'question_usages qu ON qu.id = quiza.uniqueid
                JOIN ' . ConnectionProperty::$prefijo . 'question_attempts qa ON qa.questionusageid = qu.id and qa.maxmark > 0
                JOIN ' . ConnectionProperty::$prefijo . 'question q ON qa.questionid = q.id
                JOIN ' . ConnectionProperty::$prefijo . 'question_attempt_steps qas ON qas.questionattemptid = qa.id and qas.state <> "'."todo".'" and qas.state <> "'."complete".'"
                LEFT JOIN ' . ConnectionProperty::$prefijo . 'question_attempt_step_data qasd ON qasd.attemptstepid = qas.id 
                ORDER BY us.username,quiza.quiz, quiza.attempt, qa.slot, qas.timecreated, qu.id, qas.sequencenumber, qasd.name;';
        $sqlQuery = new SqlQuery($sql);
        //$sqlQuery->setNumber($idquiz);
        //$sqlQuery->setNumber($iduser);
        return $this->getList_($sqlQuery);
    }

    public function queryBuscaIntentos($idquiz, $iduser) {
        $sql = 'SELECT quiza.attempt AS intento, 
                quiza.sumgrades AS notaFinalQuiz, 
                qa.slot AS numeroPregunta, 
                q.qtype as tipoPregunta,
                qa.maxmark AS puntajeMaximo, 
                qas.state AS estado, 
                qas.fraction AS puntajeObtenido, 
                qa.rightanswer AS respCorrecta, 
                qa.responsesummary AS respuesta, 
                us.username AS username
                FROM ' . ConnectionProperty::$prefijo . 'user us
                JOIN ' . ConnectionProperty::$prefijo . 'quiz_attempts quiza ON us.id = quiza.userid
                JOIN ' . ConnectionProperty::$prefijo . 'question_usages qu ON qu.id = quiza.uniqueid
                JOIN ' . ConnectionProperty::$prefijo . 'question_attempts qa ON qa.questionusageid = qu.id
                JOIN ' . ConnectionProperty::$prefijo . 'question q ON qa.questionid = q.id
                JOIN ' . ConnectionProperty::$prefijo . 'question_attempt_steps qas ON qas.questionattemptid = qa.id
                LEFT JOIN ' . ConnectionProperty::$prefijo . 'question_attempt_step_data qasd ON qasd.attemptstepid = qas.id
                WHERE quiza.id
                IN (
                SELECT id
                FROM  ' . ConnectionProperty::$prefijo . 'quiz_attempts 
                WHERE  quiz = ? AND userid = ?
                )
                AND qasd.name =  ' . "'-finish'" . ' AND qa.maxmark > 0
                ORDER BY quiza.quiz, quiza.attempt, qa.slot, qas.timecreated, qu.id, qas.sequencenumber, qasd.name';
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($idquiz);
        $sqlQuery->setNumber($iduser);
        return $this->getList($sqlQuery);
    }

        protected function readRow_($row) {
        $intento = new Intentos();
        $intento->intento = $row['intento'];
        $intento->notaFinalQuiz = $row['notaFinalQuiz'];
        $intento->tipoPregunta = $row['tipoPregunta'];
        $intento->numeroPregunta = $row['numeroPregunta'];
        $intento->puntajeMaximo = $row['puntajeMaximo'];
        $intento->estado = $row['estado'];
        $intento->puntajeObtenido = $row['puntajeObtenido'];
        $intento->respCorrecta = $row['respCorrecta'];
        $intento->respuesta = $row['respuesta'];
        $intento->username = $row['username'];
        $intento->nombre = $row['nombre'];
        $intento->apellido = $row['apellido'];
        return $intento;
    }

    protected function getList_($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $intento = $this->readRow_($tab[$i]);
            $ret[$i] = $intento;
        }
        return $ret;
    }
    
    
    protected function readRow($row) {
        $intento = new Intentos();
        $intento->intento = $row['intento'];
        $intento->notaFinalQuiz = $row['notaFinalQuiz'];
        $intento->tipoPregunta = $row['tipoPregunta'];
        $intento->numeroPregunta = $row['numeroPregunta'];
        $intento->puntajeMaximo = $row['puntajeMaximo'];
        $intento->estado = $row['estado'];
        $intento->puntajeObtenido = $row['puntajeObtenido'];
        $intento->respCorrecta = $row['respCorrecta'];
        $intento->respuesta = $row['respuesta'];
        $intento->username = $row['username'];
        return $intento;
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $intento = $this->readRow($tab[$i]);
            $ret[$i] = $intento;
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
