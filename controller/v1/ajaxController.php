<?php

if(!isset($_SESSION)) session_start();

Class ajaxController Extends baseController {

	public function index() {
	}
	
	/**
	 * 
	 * @param mixed $data
	 */
	private function error($data=''){
		$this->registry->template->error = $data;
		$this->registry->template->show('json');
		exit;
	}
	
	/**
	 * 
	 * @param mixed $data
	 */
	private function success($data=''){
		$this->registry->template->error = $data;
		$this->registry->template->show('json');
		exit;
	}
	
	public function login(){
		$data = array();
		$user = new user();
		$user->user_email = $_POST['email'];
		$user->user_password = $_POST['password'];
		if($user->checkCredentials()){
			$_SESSION[APP_NAME.'_user']=$user->iduser;
			$this->success($user->iduser);
		}
		else $this->error('');
	}
		
	
	
}
