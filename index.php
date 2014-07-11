<?php

 /*** error reporting on ***/
ini_set('display_errors', 1);
ini_set('log_errors', 1);

if(strpos($_SERVER['HTTP_HOST'],"local")===0) error_reporting(E_ALL);
else error_reporting(E_ERROR | E_PARSE);

 /*** define the site path ***/
 $site_path = realpath(dirname(__FILE__));
 define ('__SITE_PATH', $site_path);

 require 'config/config.php';
 
 /*** include the init.php file ***/
 include 'includes/init.php';

 // UTILS
 include 'includes/util.php';
  
 /*** load the router ***/
 $registry->router = new router($registry);

 /*** set the controller path ***/
 $registry->router->setPath (__SITE_PATH . '/controller');

 /*** load up the template ***/
 $registry->template = new template($registry);

 /*** load the controller ***/
 $registry->router->loader();

?>
