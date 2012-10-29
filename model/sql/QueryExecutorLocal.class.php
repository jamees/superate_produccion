<?php
/**
 * Object executes sql queries
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class QueryExecutorLocal{

	/**
	 * Wykonaniew zapytania do bazy
	 *
	 * @param sqlQuery obiekt typu SqlQuery
	 * @return wynik zapytania 
	 */
	public static function execute($sqlQuery){
                //$paso = "<br> execute";
		$transaction = TransactionLocal::getCurrentTransaction();
		if(!$transaction){
			$connection = new ConnectionLocal();
		}else{
			$connection = $transaction->getConnectionLocal();
		}		
		$query = $sqlQuery->getQuery();
		$result = $connection->executeQueryLocal($query);
		if(!$result){
			throw new Exception(mysql_error());
		}
		$i=0;
		$tab = array();
		while ($row = mysql_fetch_array($result)){
			$tab[$i++] = $row;
		}
		mysql_free_result($result);
		if(!$transaction){
			$connection->closeLocal();
		}
		return $tab;
	}
	
	
	public static function executeUpdate($sqlQuery){
		$transaction = TransactionLocal::getCurrentTransaction();
		if(!$transaction){
			$connection = new ConnectionLocal();
		}else{
			$connection = $transaction->getConnection();
		}		
		$query = $sqlQuery->getQuery();
                
		$result = $connection->executeQueryLocal($query);
                
		if(!$result){
			throw new Exception(mysql_error());
		}
		return mysql_affected_rows();		
	}

	public static function executeInsert($sqlQuery){
            QueryExecutorLocal::executeUpdate($sqlQuery);
            return mysql_insert_id();
	}
	
	/**
	 * Wykonaniew zapytania do bazy
	 *
	 * @param sqlQuery obiekt typu SqlQuery
	 * @return wynik zapytania 
	 */
	public static function queryForString($sqlQuery){
		$transaction = TransactionLocal::getCurrentTransaction();
		if(!$transaction){
			$connection = new ConnectionLocal();
		}else{
			$connection = $transaction->getConnection();
		}
		$result = $connection->executeQueryLocal($sqlQuery->getQuery());
		if(!$result){
			throw new Exception(mysql_error());
		}
		$row = mysql_fetch_array($result);		
		return $row[0];
	}

}
?>