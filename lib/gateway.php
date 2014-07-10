<?php

/** 
 * @author mauriciovander
 *
 */
class gateway {
	// TODO - Insert your code here
	private $gateway_url;
	private $gateway_user;
	private $gateway_password;
	
	/**
	 */
	function __construct() {
		$this->gateway_url = 'https://' . GATEWAY_SERVER_ADDRESS;
		$this->gateway_user = GATEWAY_SERVER_USER;
		$this->gateway_password = GATEWAY_SERVER_PASSWORD;
	}
	
	/**
	 */
	function __destruct() {
		
		// TODO - Insert your code here
	}
	
	
	/**
	 * This method is used for registering a user in the gateway
	 * @param unknown $email
	 * @param unknown $ripple_address
	 * @return Ambigous <boolean, mixed>|boolean
	 */
	function register($email,$ripple_address){
		
		// validate ripple address:
		$result = $this->get('/v1/trustlines/'.$ripple_address);
		if($result && $result->success){
			
			// generate a Unique ID
			$uid = uniqid();
			
			// register user in the gateway
			$data = array(
					'name'=>$email,
					'password'=>$uid,
					'ripple_address'=>$ripple_address,
			);
			$result = $this->post('/v1/registrations',$data);
			if($result && $result->success) return $result;
		}
		return false;		
	}
	
	
	
	
	private function post($method, $data = array()) {
		$url = $this->gateway_url . $method;
		$encoded = http_build_query ( $data ); // substr($encoded, 0, strlen($encoded)-1);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_USERPWD, $this->gateway_user . ":" . $this->gateway_password );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // +
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $encoded );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		// $cert = $_SERVER ['DOCUMENT_ROOT'] . "/cert/ripple-server.crt";
		// curl_setopt($ch, CURLOPT_SSLCERT, $cert); // TODO: fix this so I can trust the SSL certificate
		// curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
		
		$json = trim ( curl_exec ( $ch ) );
		curl_close ( $ch );		
		
		$obj = json_decode($json);
		if(json_last_error()==JSON_ERROR_NONE) return $obj;
		return false;
		
	}
	
	private function get($method, $data = array()) {
		$url = $this->gateway_url.$method;
		if (count ( $data )) $url .= '?' . http_build_query ( $data );
				
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HTTPGET, 1 );
		curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_USERPWD, $this->gateway_user . ":" . $this->gateway_password );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		// $cert = $_SERVER ['DOCUMENT_ROOT'] . "/cert/ripple-server.crt";
		// curl_setopt($ch, CURLOPT_SSLCERT, $cert); // TODO: fix this so I can trust the SSL certificate
		// curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
		
		$json = trim ( curl_exec ( $ch ) );
		curl_close ( $ch );
		
		$obj = json_decode($json);
		if(json_last_error()==JSON_ERROR_NONE) return $obj;
		return false;
	}
}

?>