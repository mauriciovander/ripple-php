<?php

if(!isset($_SESSION)) session_start();

Class ajaxController Extends baseController {

	public function __construct($registry){
		parent::__construct($registry);
		if(!isset($_SESSION[APP_NAME.'_user'])) header('location: /v1/index/login');
	}
	
	public function index() {				
		/*** load the index template ***/
	    $this->registry->template->show();
	}
		
}
