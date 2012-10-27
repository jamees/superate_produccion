<?php

class LogMySqlDAO implements LogDAO {

    public function queryLogs($userid) {
        $sql = "SELECT  id, time, userid, ip, course, module, cmid, action, url, info
               FROM " . ConnectionProperty::$prefijo . "log
               WHERE  userid = ? 
               ORDER BY time; ";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($userid);
        return $this->getList($sqlQuery);
    }
    
    public function queryCantidadLogsIntervalo($userid, $fecha_inicio, $fecha_fin) {
        $sql = "SELECT count(*) as total 
               FROM " . ConnectionProperty::$prefijo . "log
               WHERE  userid = ? AND from_unixtime(time) > ? AND from_unixtime(time) < ?; ";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($userid);
        $sqlQuery->setString($fecha_inicio);
        $sqlQuery->setString($fecha_fin);
        return $this->getTotal($sqlQuery);
    }

     public function queryCantidadLogsIntervaloCurso($userid, $fecha_inicio, $fecha_fin, $curso) {
        $sql = "SELECT count(*) as total 
               FROM " . ConnectionProperty::$prefijo . "log
               WHERE  userid = ? AND from_unixtime(time) > ? AND from_unixtime(time) < ? AND course = ?; ";
        $sqlQuery = new SqlQuery($sql);
        $sqlQuery->setNumber($userid);
        $sqlQuery->setString($fecha_inicio);
        $sqlQuery->setString($fecha_fin);
        $sqlQuery->setNumber($curso);
        return $this->getTotal($sqlQuery);
    }
    protected function readRow($row) {
        $log = new Log();
        $log->action = $row['action'];
        $log->cmid = $row['cmid'];
        $log->course = $row['course'];
        $log->id = $row['id'];
        $log->info = $row['info'];
        $log->ip = $row['ip'];
        $log->module = $row['module'];
        $log->time = $row['time'];
        $log->url = $row['url'];
        $log->userid = $row['userid'];
        return $log;
    }
    
    protected function readTotal($row) {
        return  $row['total'];
    }

    protected function getList($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        $ret = array();
        for ($i = 0; $i < count($tab); $i++) {
            $quiz = $this->readRow($tab[$i]);
            $ret[$i] = $quiz;
        }
        return $ret;
    }

    protected function getRow($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readRow($tab[0]);
    }
    
    protected function getTotal($sqlQuery) {
        $tab = QueryExecutor::execute($sqlQuery);
        if (count($tab) == 0) {
            return null;
        }
        return $this->readTotal($tab[0]);
    }
    

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
