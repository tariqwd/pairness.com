<?php
include('../includes/functions.php');
if(1){


echo do_login(request_var('username',''),request_var('password',''),request_var('remember',''),request_var('return',''));
}else{

$arr = array('s' => 0,'e'=>'Only POST requests are accepted');
				echo json_encode($arr);
}
?>