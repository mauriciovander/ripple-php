<?php

Class Template {

/*
 * @the registry
 * @access private
 */
private $registry;

/*
 * @Variables array
 * @access private
 */
private $vars = array();

/**
 *
 * @constructor
 *
 * @access public
 *
 * @return void
 *
 */
function __construct($registry) {
	$this->registry = $registry;

}


 /**
 *
 * @set undefined vars
 *
 * @param string $index
 *
 * @param mixed $value
 *
 * @return void
 *
 */
 public function __set($index, $value)
 {
        $this->vars[$index] = $value;
 }


function show($name=NULL) {
	
	$path = __SITE_PATH . '/views' . '/' . $name . '.php';
	
	if (!file_exists($path)){
		$path = __SITE_PATH . '/views' . '/' . $this->registry->router->module . '/' . 
				$this->registry->router->controller . '/' .  
				$this->registry->router->action . '.php';
		if (!file_exists($path)){	
			throw new Exception('Template not found in '. $path);
			return false;
		}
	}
	
	// Load variables
	foreach ($this->vars as $key => $value)
	{
		$$key = $value;
	}

	
	include (__SITE_PATH . '/views/header.php');               
	include ($path);               
	include (__SITE_PATH . '/views/footer.php');               
}


}

?>
