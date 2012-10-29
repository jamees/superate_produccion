<?php
/**
 * Database transaction
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class TransactionLocal{
	private static $transactionsLocal;

	private $connectionLocal;

	public function TransactionLocal(){
		$this->connectionLocal = new ConnectionLocal();
		if(!TransactionLocal::$transactionsLocal){
			TransactionLocal::$transactionsLocal = new ArrayList();
		}
		TransactionLocal::$transactionsLocal->add($this);
		$this->connectionLocal->executeQueryLocal('BEGIN');
	}

	/**
	 * Zakonczenie transakcji i zapisanie zmian
	 */
	public function commit(){
		$this->connectionLocal->executeQueryLocal('COMMIT');
		$this->connectionLocal->closeLocal();
		TransactionLocal::$transactionsLocal->removeLast();
	}

	/**
	 * Zakonczenie transakcji i wycofanie zmian
	 */
	public function rollback(){
		$this->connectionLocal->executeQueryLocal('ROLLBACK');
		$this->connectionLocal->closeLocal();
		TransactionLocal::$transactionsLocal->removeLast();
	}

	/**
	 * Pobranie polaczenia dla obencej transakcji
	 *
	 * @return polazenie do bazy
	 */
	public function getConnection(){
		return $this->connectionLocal;
	}

	/**
	 * Zwraca obecna transakcje
	 *
	 * @return transkacja
	 */
	public static function getCurrentTransaction(){
		if(TransactionLocal::$transactionsLocal){
			$tran = TransactionLocal::$transactionsLocal->getLast();
			return $tran;
		}
		return;
	}
}
?>