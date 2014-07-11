<?php
class model {
	protected $db;
	private $fields;
	
	public function __construct() {
		$this->db = db::getInstance ();
		$this->fields = $this->getFields();
	}
	
	public function __set($field, $value) {
		$this->{$field} = $value;
	}
	
	public function __get($field) {
		return isset ( $this->{$field} ) ? $this->{$field} : null;
	}
	
	public function insert(){
		$values1_sql = array ();
		$values2_sql = array ();
		foreach ( $this->fields as $field ) {
			if (! strpos ( $field, '_date_created' ) 
				&& ! strpos ( $field, '_last_update' ) 
				&& isset ( $this->{$field} )) {
				$values1_sql [] = $this->db->escape ( $field );
				$values2_sql [] = '"' . $this->db->escape ( $this->{$field} ) . '"';
			}
		}
		$values1_sql[] = get_class ( $this ) . '_date_created';
		$values2_sql[] = 'now()';
		
		$sql = 'insert into ' . DB_NAME . '.' . get_class ( $this ) . '
		(' . join ( ',', $values1_sql ) . ') values (' . join ( ',', $values2_sql ) . ') ';

		$this->{'id'.get_class ( $this )} = $this->db->insert ( $sql );
		return $this->{'id'.get_class ( $this )} ;
	}
	
	public function save() {
		
		$set_sql = array ();
		$values1_sql = array ();
		$values2_sql = array ();
		foreach ( $this->fields as $field ) {
			if (! strpos ( $field, '_date_created' ) && ! strpos ( $field, '_last_update' ) && isset ( $this->{$field} )) {
				
				$set_sql [] = $field . ' = "' . $this->db->escape ( $this->{$field} ) . '"';
				$values1_sql [] = $this->db->escape ( $field );
				$values2_sql [] = '"' . $this->db->escape ( $this->{$field} ) . '"';
			}
		}
		$values1_sql[] = get_class ( $this ) . '_date_created';
		$values2_sql[] = 'now()';		
		
		$sql = 'update ' . DB_NAME . '.' . get_class ( $this ) . '
				set ' . join ( ',', $set_sql ).'
				where id'.get_class ( $this ).' = '.$this->db->escape($this->{'id'.get_class ( $this )}).'
				limit 1';
		
			return $this->db->update( $sql );
	}
	
	public function getFields() {
		$sql = 'explain ' . DB_NAME . '.' . get_class($this);
		$objlist = $this->db->query ( $sql );
		$r = array ();
		foreach ( $objlist as $obj ) {
			if ($obj->Key != 'PRI')
				$r [] = $obj->Field;
		}
		return $r;
	}
	
	public static function createTable() {
		$db = db::getInstance ();
		$sql = 'SHOW TABLES LIKE "' .  get_called_class() . '"';
		$r = $db->query($sql);
		if (count($r)==1) return true;

		$sql = 'CREATE TABLE `' . DB_NAME . '`.`' . get_called_class() . '` (
			`id' . get_called_class() . '` INT NOT NULL AUTO_INCREMENT,
			`' . get_called_class() . '_date_created` DATE NULL,
			`' . get_called_class() . '_last_update` TIMESTAMP NULL DEFAULT current_timestamp on update current_timestamp,
			PRIMARY KEY (`id' . get_called_class() . '`))';
		
		return $db->insert ( $sql );		
	}
	
	public static function load($id) {
		$db = db::getInstance ();
		$sql = 'select *
		from ' . DB_NAME . '.' . get_called_class() . '
		where id' . get_called_class () . ' = "' . $db->escape ( $id ) . '"
		limit 1';
		$objlist = $db->query ( $sql );
		if (count ( $objlist )) {
			$className = get_called_class ();
			$o = new $className();
			
			$obj = $objlist[0];
			foreach ( array_keys ( ( array ) $obj ) as $field ) {
				$o->{$field} = $obj->{$field};
			}
			return $o;
		}
		return false;
	}
}