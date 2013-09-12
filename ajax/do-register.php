<?php

include('../includes/functions.php');
		

			if(1)
			{
				
				
				
				 $ip = $_SERVER['REMOTE_ADDR'];
				 $email =  request_var('email','');
				 $username = request_var('username','');
				 $name = request_var('name','');
				 $password = request_var('password','');
				 $isfamily = request_var('isfamily',0);
				 $policy = request_var('policy',0);
				
				 
				 
				$t = time();
				$sc = session_id();
				
					
				
				
				if($email!='' && $username!='' && $password!='' && $policy!='0'){
					$tg = $mysqli->query("select * from login where email = '$email'");
				
					if($tg->num_rows ==0){
						$tp = $mysqli->query("select * from login where username = '$username'");
						if($tp->num_rows ==0){
							$p = "INSERT INTO login (name,ip,isfamily,username,email,password,registertime,mobileverification,emailverification,sc,verificationdate,privacylevel)VALUES ('$name','$ip','$isfamily','$username','$email','$password','$t','0','0','$sc','0','1')";
							
								
							$q = "INSERT INTO pairness_family (ip,familyid,familyfullname,familyemailaddress,familypassword,familyprofileimage)VALUES('$ip','$isfamily','$username','$email','$password','default.png')";
							$mysqli->query($q);


							if($mysqli->query($p)){
								$arr = array('s' => 1,'v'=>'Please check your email for further instructions');
								echo json_encode($arr);
							}else{
								$arr = array('s' => 0,'e'=>'There was a problem. We have dispatched monkeys to fix it');
								echo json_encode($arr);
							}
				
						}else{
							$arr = array('s' => 0,'e'=>'Username is already taken.');
							echo json_encode($arr);
						}
					}else{
						$arr = array('s' => 0,'e'=>'Email address is already in use.');
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