<?php
/*********************************************

	Copyright:	Cozmuler Pakistan
	File Name:	home.php
	Package:	Pairness.com
	Author:		Rahber

*********************************************/
include('./includes/functions.php');

echo urlencode(encrypt_text("zubair"));

echo "<br /><br />";

echo request_var('name','');
?>