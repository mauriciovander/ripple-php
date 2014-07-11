<?php

/**
 * MYSQL Table Creation
 * CREATE TABLE `user` (
 * `iduser` int(11) NOT NULL AUTO_INCREMENT,
 * `user_date_created` date DEFAULT NULL,
 * `user_last_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 * `user_email` varchar(100) DEFAULT NULL,
 * `user_password` varchar(45) DEFAULT NULL,
 * `user_idkyc` int(11) DEFAULT NULL,
 * `user_idbank` int(11) DEFAULT NULL,
 * `user_idripple` int(11) DEFAULT NULL,
 * `user_ripple_address` varchar(100) DEFAULT NULL,
 * PRIMARY KEY (`iduser`),
 * UNIQUE KEY `user_email_UNIQUE` (`user_email`)
 * )
 *
 * @author mauricio
 *
 */
class user extends model{
	
	/**
	 * check user credentials 
	 * $this->user_email, 
	 * $this->user_password
	 * @return boolean
	 */
	public function checkCredentials(){
		$sql = 'select * from '.DB_NAME.'.user 
				where user_email = "'.$this->db->escape($this->user_email).'"
				and user_password = md5("'.$this->db->escape($this->user_password).'")
				limit 1' ;
		$rows = $this->db->query($sql);
		if(count($rows)==1){
			$row = $rows[0];
			foreach ( array_keys ( ( array ) $row ) as $field ) {
				$this->{$field} = $row->{$field};
			}
			return true;
		}
		return false;
	}	
}