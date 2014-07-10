<?php

/** 
 * @author mauriciovander
 *
 */
class gateway {
	private $gateway_url;
	private $gateway_user;
	private $gateway_password;
	private $gateway_cold_wallet;
	
	/************************************************************************************/
	
	/**
	 */
	function __construct() {
		$this->gateway_url = 'https://' . GATEWAY_SERVER_ADDRESS;
		$this->gateway_user = GATEWAY_SERVER_USER;
		$this->gateway_password = GATEWAY_SERVER_PASSWORD;
		$this->gateway_cold_wallet = GATEWAY_COLD_WALLET;
	}
	
	/************************************************************************************/
	
	/**
	 */
	function __destruct() {
		// TODO - Insert your code here
	}
	
	/************************************************************************************/
	
	
	/**
	 * This method is used for registering a user in the gateway
	 * @param string $email
	 * @param string $ripple_address
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
	
	/************************************************************************************/
	
	/**
	 * Check for valid trustlines between the user and the cold wallet
	 * Logic explained inline
	 * @param string $ripple_address
	 * @param string $currency
	 * @param double $amount
	 * @return Ambigous boolean|double|array
	 */
	function trustlines($ripple_address,$currency=NULL,$amount=NULL){
		$result = $this->get('/v1/trustlines/'.$ripple_address);
		
		if(!$result || !$result->success) return false;
		if(!isset($result->trustlines)) return false;

		// search for trust lines between the user and the gateway
		$trustlines = array();
		foreach($result->trustlines as $trustline){
			if($trustline->account == $this->gateway_cold_wallet){
				$trustlines[$trustline->currency] = $trustline->limit;
			}
		}
		
		// if no trustlines are found between the user and the gateway, 
		// method should return false
		if(empty($trustlines)) return false;
		
		// if no currency is set as a parameter, returns all trustlines for this gateway
		// as an array of currency=>limit pairs
		if(is_null($currency)) return $trustlines;
		
		// if currency parameter is set, but no trust line is found for this 
		// particular currency, return false 
		if(!array_key_exists($currency, $trustlines)) return false;
		
		// if no amount parameter is set, just return the trustline limit for selected currency
		if(is_null($amount)) return $trustlines[$currency];

		// if amount is set, returns true if amount is below the trustline limit, 
		// or false if amount exceeds the trustline limit 
		return $trustlines[$currency]>=$amount;				
	}
	
	/************************************************************************************/
	
	/** performs curl -X POST to the gateway
	 * 
	 * @param string $method
	 * @param array $data (key=>value pairs)
	 * @return array|boolean
	 */
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
	
	/************************************************************************************/
	
	/** performs curl -X GET to the gateway
	 * 
	 * @param string $method
	 * @param array $data (key=>value pairs)
	 * @return array|boolean
	 */
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
	
	/************************************************************************************/
	
}

?>