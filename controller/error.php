<?php

Class errorController Extends baseController {

	public function index() 
	{
	        $this->registry->template->n = 404;
	        $this->registry->template->show('error');
	}

}