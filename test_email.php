<?php 



	$from = 'pairness.com';
	$headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= "From: ".$from. "\r\n";

 
    mail('mtariq_sarwar@yahoo.com', 'Cron job test email', 'Cron job successfull executed', $headers);



?>