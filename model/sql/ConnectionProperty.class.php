<?php
/**
 * Connection properties
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class ConnectionProperty{
    
        //Atributos conexion Local
	private static $hostLocal = '127.0.0.1';
	private static $userLocal = 'root';
	private static $passwordLocal = 'ROOT';
	private static $databaseLocal = 'analytics_superate';
        
        //Atributos conexion Plataforma 
	private static $hostPlataforma = '127.0.0.1';
	//private static $hostPlataforma = '10.179.224.134';
	private static $userPlataforma = 'root';
	private static $passwordPlataforma = 'ROOT';
	private static $databasePlataforma = 'moodle_universidad_db';   
        public static $prefijo = 'universidad_';

        //prefijo base datos plataforma
	public static function getPrefijoPlataforma(){
		return $this->$prefijo;
	}
        
        //Metodos conexion local
	public static function getHostLocal(){
		return ConnectionProperty::$hostLocal;
	}

	public static function getUserLocal(){
		return ConnectionProperty::$userLocal;
	}

	public static function getPasswordLocal(){
		return ConnectionProperty::$passwordLocal;
	}

	public static function getDatabaseLocal(){
		return ConnectionProperty::$databaseLocal;
	}
        
        //Metodos conexion Plataforma
	public static function getHostPlataforma(){
		return ConnectionProperty::$hostPlataforma;
	}

	public static function getUserPlataforma(){
		return ConnectionProperty::$userPlataforma;
	}

	public static function getPasswordPlataforma(){
		return ConnectionProperty::$passwordPlataforma;
	}

	public static function getDatabasePlataforma(){
		return ConnectionProperty::$databasePlataforma;
	}        
}
?>
