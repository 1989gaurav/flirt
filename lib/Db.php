<?php

class Db {
	protected $conn;
	public function __construct() {
		$this->conn = @mysql_connect('sql102.byethost9.com', 'b9_5489101', 'hack2012');
		if( $this->conn==false ) {
			$err = mysql_errno();
			if( $err==1203 ) {
				die('Our database server is overcome with connections. Please try after some time.');
			}
			die('DB::constructor -- could not connect to db host!');
		}
		@mysql_select_db('b9_5489101_openhack2012', $this->conn)
			or die('DB::constructor -- could not select db!');
	}
	public function query($q) {
		return @mysql_query($q, $this->conn);
	}
	public function escape($f, $addslashes=false) {
		if( $addslashes==false ) {
			if( get_magic_quotes_gpc() ) $f = stripslashes($f);
		} else {
			if( !get_magic_quotes_gpc() ) $f = addslashes($f);
		}
		return mysql_real_escape_string($f, $this->conn);
	}
	public function getNumRowsAffected() {
		return mysql_affected_rows($this->conn);
	}
	public function getAllRows($result, $how=MYSQL_NUM) {
		$arr = array();
		while( $row = mysql_fetch_array($result, $how) ) {
			array_push( $arr, $row );
		}
		return $arr;
	}
	public function getNextRow($result, $how=MYSQL_NUM) {
		return mysql_fetch_array($result, $how);
	}
	public function getNumRows($result) {
		return mysql_num_rows($result);
	}
	public function getInsertId() {
		return mysql_insert_id($this->conn);
	}
	public function getError() {
		return mysql_error($this->conn);
	}
}

?>
