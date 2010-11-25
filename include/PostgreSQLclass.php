<?
/* =============================================================================
File : postgreSQLclass.php
================================================================================
Coded yoonani(yoonani@hallym.ac.kr)
================================================================================
Date : 2005. 7. 9
================================================================================
Desc.
	PostgreSQL�� ����ϱ� ���� Class
	MySQLclass�� PostgreSQL������ Porting
============================================================================= */


class PostgreSQL {
	var $host;
	var $user;
	var $pwd;
	var $dbName;
	var $persistent;

	var $conn = NULL;

	function PostgreSQL($host, $user, $pwd, $dbName, $db=NULL, $persistent = false) {
		$this->host = $host;
		$this->user = $user;
		$this->pwd = $pwd;
		$this->dbName = $dbName;
		$this->persistent = $persistent;
	}

	function connect() {
		if($this->persistent) {
			$connect = 'pg_pconnect';
		} else {
			$connect = 'pg_connect';
		}
		$this->conn = $connect(" dbname=" . $this->dbName . " user=" . $this->user . " password=" . $this->pwd);
		if(!$this->conn) {
			return false;
		} else {
			return true;
		}
	}

	function close() {
		return(@pg_close($this->conn));
	}

	function error() {
		return(pg_last_error($this->conn));
	}

	function query($sql) {
		// pg_query >= PHP4.2.0, PHP5
		$this->result = @pg_query($this->conn, $sql);
		return($this->result != false);
	}

	// �������ڰ� N�� �ƴϸ� mysql_affected_rows
	function noRows($type="N") {
		if($type!="N") {
			return(@pg_affected_rows($this->result));
		} else {
			return(@pg_num_rows($this->result));
		}
	}

	// �������ڰ� N : PGSQL_NUM 
	// �������ڰ� A : PGSQL_ASSOC
	// �������ڰ� B : PGSQL_BOTH
	function fetch($type="N") {
		switch($type) {
			case "A" :
				return(@pg_fetch_array($this->result, NULL, PGSQL_ASSOC));
				break;
			case "B" :
				return(@pg_fetch_array($this->result, NULL, PGSQL_BOTH));
				break;
			case "N" :
				return(@pg_fetch_array($this->result, NULL, PGSQL_NUM));
				break;
		}
	}

	function getResult($intRow, $intCol) {
		return(@pg_fetch_result($this->result, $intRow, $intCol));
	}

	function freeResult() {
		return(@pg_free_result($this->result));
	}
}
