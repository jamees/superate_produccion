<?php

/**
 * Object represents connection to database
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class ConnectionLocal {

    private $connectionLocal;
    
    //Conexion a base de datos Local de reportes
    public function ConnectionLocal() {
        $this->connectionLocal = ConnectionFactory::getConnectionLocal();
    }

    public function closeLocal() {
        ConnectionFactory::close($this->connectionLocal);
    }

    /**
     * Wykonanie zapytania sql na biezacym polaczeniu
     *
     * @param sql zapytanie sql
     * @return wynik zapytania
     */
    public function executeQueryLocal($sql) {
        return mysql_query($sql, $this->connectionLocal);
    }

}

?>