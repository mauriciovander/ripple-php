<?php

class db{

	private static $singleton_instance = null;
	protected $mysqli;

	private function __construct() {
		$this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
	}

	public function __destruct() {
		$this->mysqli->close();
	}

	public static function getInstance() {
		static $singleton_instance = null;
		if($singleton_instance === null) {
			$singleton_instance = new db();
		}
		return($singleton_instance);
	}

	public function escape($string){
		$escaped = $this->mysqli->real_escape_string($string);
		return $escaped;
	}

	public function insert($sql){
		$this->mysqli->query($sql);
		$pri_key = $this->mysqli->insert_id;
		return $pri_key;

	}

	public function update($sql){
		$this->mysqli->query($sql) or die ($this->mysqli->error);
	}

	public function query($sql) {
		$result = $this->mysqli->query($sql) or die ($this->mysqli->error);

		$object_list = array();
		while ($obj = $result->fetch_object() ){
			$object_list[] = $obj;
		}
		return $object_list;
	}

	/**
	 *
	 * Like the constructor, we make __clone private
	 * so nobody can clone the instance
	 *
	 */
	private function __clone(){
	}

} /*** end of class ***/

