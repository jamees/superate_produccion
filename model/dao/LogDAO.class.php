<?php

interface LogDAO {

    public function queryLogs($userid);

    public function queryCantidadLogsIntervalo($userid, $fecha_inicio, $fecha_fin);

    public function queryCantidadLogsIntervaloCurso($userid, $fecha_inicio, $fecha_fin, $curso);
}

?>