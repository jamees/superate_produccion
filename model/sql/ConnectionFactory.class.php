<?php
/*
 * Class return connection to database
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class ConnectionFactory{
	
	/**
	 * Zwrocenie polaczenia
	 *
	 * @return polaczenie
	 */
	static public function getConnectionLocal(){
		$conn1 = mysql_connect(ConnectionProperty::getHostLocal(), ConnectionProperty::getUserLocal(), ConnectionProperty::getPasswordLocal());
		mysql_select_db(ConnectionProperty::getDatabaseLocal());
		if(!$conn1){
			throw new Exception('No se puede conectar a la base de datos local');
		}
                mysql_query("SET NAMES 'utf8'");
		return $conn1;
	}
        
        static public function getConnectionPlataforma(){
		$conn = mysql_connect(ConnectionProperty::getHostPlataforma(), ConnectionProperty::getUserPlataforma(), ConnectionProperty::getPasswordPlataforma());
		mysql_select_db(ConnectionProperty::getDatabasePlataforma());
		if(!$conn){
			throw new Exception('No se puede conectar a la base de datos de la plataforma');
		}
                mysql_query("SET NAMES 'utf8'");
		return $conn;
	}

	/**
	 * Zamkniecie polaczenia
	 *
	 * @param connection polaczenie do bazy
	 */
	static public function close($connection){
		mysql_close($connection);
	}
}
?>