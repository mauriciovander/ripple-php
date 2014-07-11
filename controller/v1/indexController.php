<?php

if(!isset($_SESSION)) session_start();

Class indexController Extends baseController {

	public function __construct($registry){
		parent::__construct($registry);
		if(!isset($_SESSION[APP_NAME.'_user']) && 
		   !in_array($this->registry->router->action , array('login','register','index'))) 
				header('location: /v1/index/login');
	}
	
	public function index() {				
		/*** load the index template ***/
	    $this->registry->template->show();
	}
	
	
	public function login(){
		$this->registry->template->show();
	}
	
	public function register(){
		$this->registry->template->show();
	}
	
	public function logout(){
		if(!isset($_SESSION[APP_NAME.'_user'])) unset($_SESSION[APP_NAME.'_user']);
		header('location: /v1/index/login');
	}
		
}
