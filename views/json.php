<?php

$obj = new stdClass();
$obj->success = false;

if(isset($error)){
	if(!empty($error)) $obj->data = $error;
}
else if(isset($success)){
	$obj->success = true;
	if(!empty($success)) $obj->data = $success;	
}

$json = json_encode($obj);

if(json_last_error()!=JSON_ERROR_NONE) echo $json;
else echo '{"success":false}';

exit;

