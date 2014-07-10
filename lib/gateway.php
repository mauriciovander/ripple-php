<?php

/** 
 * @author mauriciovander
 *
 */
class gateway {
	// TODO - Insert your code here
	private $gateway_url;
	/**
	 */
	function __construct() {
		$this->gateway_url = 'https://' . GATEWAY_SERVER_IP;
	}
	
	/**
	 */
	function __destruct() {
		
		// TODO - Insert your code here
	}
	
	public function post($method, $data = array()) {
		$url = $this->gateway_url . $method;
		$encoded = http_build_query ( $data ); // substr($encoded, 0, strlen($encoded)-1);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_USERPWD, RIPPLE_SERVER_USER . ":" . RIPPLE_SERVER_PASS );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 ); // +
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $encoded );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		// $cert = $_SERVER ['DOCUMENT_ROOT'] . "/cert/ripple-server.crt";
		// curl_setopt($ch, CURLOPT_SSLCERT, $cert); // TODO: fix this so I can trust the SSL certificate
		// curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
		
		$output = trim ( curl_exec ( $ch ) );
		curl_close ( $ch );		
		return $output;
	}
	
	public function get($method, $data = array()) {
		$url = $this->gateway_url.$method;
		if (count ( $data )) $url .= '?' . http_build_query ( $data );
				
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HTTPGET, 1 );
		curl_setopt ( $ch, CURLOPT_VERBOSE, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_FAILONERROR, 0 );
		curl_setopt ( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt ( $ch, CURLOPT_USERPWD, RIPPLE_SERVER_USER . ":" . RIPPLE_SERVER_PASS );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		// $cert = $_SERVER ['DOCUMENT_ROOT'] . "/cert/ripple-server.crt";
		// curl_setopt($ch, CURLOPT_SSLCERT, $cert); // TODO: fix this so I can trust the SSL certificate
		// curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
		
		$output = trim ( curl_exec ( $ch ) );
		curl_close ( $ch );
		return $output;
	}
}

?>