<?php

include('../includes/functions.php');
		

			if(1)
			{
				
				
				
				 $ip = $_SERVER['REMOTE_ADDR'];
				 $fromid = $_SESSION['id'];
				 $fromuser = get_name($fromid);
				 $subject =  request_var('email_subject','');
				 $subject = $mysqli->real_escape_string($subject);
				 $message = request_var('msg','');
				 $message = $mysqli->real_escape_string($message);
				 $toid = request_var('toid','');
				 $touser = request_var('touser','');
				 //$password = request_var('password','');
				 $isfamily = request_var('isfamily',0);
				 //$policy = request_var('policy',0);

				$t = time();
				$sc = session_id();
	
				if($subject!='' && $message!='' && $toid!=''){
					
							
							$q = "INSERT INTO pairness_message (fromid,fromuser,toid,touser,messagetitle,messagecontent,messageread)VALUES('$fromid','$fromuser','$toid','$touser','$subject','$message','0')";
							//$mysqli->query($q);
							
							if($mysqli->query($q)){
								$arr = array('s' => 1,'v'=>'Email sended Successfully');
								echo json_encode($arr);
							}else{
								$arr = array('s' => 0,'e'=>'There was a problem. We have dispatched monkeys to fix it');
								echo json_encode($arr);
							}	
					
				}else{
					$arr = array('s' => 0,'e'=>'Please fill all fields');
					echo json_encode($arr);
					}
			}else{
				$arr = array('s' => 0,'e'=>'Only Post requests are accepted');
				echo json_encode($arr);
			}
				
			
				
				?>