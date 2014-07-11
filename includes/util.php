<?php

/** 
 * @author mauriciovander
 * 
 */
class util {
	
	/**
	 * This method creates a simple control hash using the defined APP_CONTROL_SECRET
	 * Create your own control hash
	 * @param array $mixed
	 */
	static function controlHash($mixed){
		$data = json_encode($mixed);
		if(json_last_error()!=JSON_ERROR_NONE) $data = $mixed;
		return sha1(md5($data).APP_CONTROL_SECRET);
	}
	
	/**
	 * @param mixed $mixed
	 * @param string $control
	 * @return boolean
	 */
	static function controlHashCheck($mixed,$control){
		return self::controlHash($mixed)==$control;
	}
	
}

?>