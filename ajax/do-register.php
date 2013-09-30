<?php

include('../includes/functions.php');
		

			if(1)
			{
				
				
				
				 $ip = $_SERVER['REMOTE_ADDR'];
				 $email =  request_var('email','');
				 $username = request_var('username','');
				 $name = request_var('name','');
				 $password = request_var('password','');
				 $gender = request_var('gender','');
				 $isfamily = request_var('isfamily',0);
				 $policy = request_var('policy',0);
				 $chkemail = 0;
				 $chkname = 0;
				 $t = time();
				 $sc = session_id();
				
				if(!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", $email))
				{ 
					$chkemail++;
				}	
				
				if(preg_match("/[0-9]+/", $name))
				{
					$chkname++;		
				}
				

				if($email!='' && $username!='' && $password!='' && $gender!='' && $policy!='0' && !$chkemail && !$chkname){
						$tg = $mysqli->query("select * from login where email = '$email'");
					
						if($tg->num_rows ==0){
							$tp = $mysqli->query("select * from login where username = '$username'");
							if($tp->num_rows ==0){
								$p = "INSERT INTO login (name,ip,isfamily,username,email,password,registertime,mobileverification,emailverification,sc,verificationdate,privacylevel)VALUES ('$name','$ip','$isfamily','$username','$email','$password','$t','0','0','$sc','0','1')";
								
									
								$q = "INSERT INTO pairness_family (ip,familyid,familyfullname,familyemailaddress,familypassword,familygender,familyprofileimage)VALUES('$ip','$isfamily','$username','$email','$password','$gender','default.png')";
								$mysqli->query($q);
	
								if($mysqli->query($p))
								{
									
									  $message = '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:solid 5px #025285;">';
									  $message .=  '<tr>';
									  $message .=    '<th colspan="2" scope="row" align="center"><span style="color:#025285;"><hr/></span></th>';
									  $message .=  '</tr>';
									  $message .= '<tr>';
									  $message .=    '<td align="left" valign="top">';
									  $message .=		'<table width="400" border="0" cellspacing="0" cellpadding="0" style="padding-top:20px;">';
									  $message .=		  '<tr>';
									  $message .=			'<th scope="row"><h2 style="color:#025285; font-family:Arial, Helvetica, sans-serif; text-align:center;">Pairness Account Details</h2></th>';
									  $message .=		  '</tr>';
									  $message .=		  '<tr>';
									  $message .=		  	'<td>&nbsp;</td>';
									  $message .=		  '</tr>';
									  $message .=		  '<tr>';
									  $message .=			'<td style="color:#464646; padding-left:15px; font-family:Arial, Helvetica, sans-serif;">';
									  $message .=				'<span style=""><strong>Username: </strong>'.$username.'</span> <br />';
									  $message .=				'<p><br /></p>';
									  $message .=				'<span style=""><strong>Email Address: </strong>'.$email.'</span> <br />';
									  $message .=				'<p><br /></p>';
									  $message .=				'<span style=""><strong> Security Key: </strong>'.$sc.'</span>';
									  $message .=				'<p><br /> <br /></p>';
									  $message .=				'<p style="font-size:13px;">';
									  $message .=					'Please save this information, This will required for your account verification.<br />';
									  $message .=				'</p>';
									  $message .=				'<p style="font-size:13px;">';
									  $message .=					'<a href="http://www.cozmuler.com/projects/dev/pairness.com/verify.php">Click Here</a> to Complete your Registration<br />';
									  $message .=				'</p>';
									  $message .=				'<p></p>';
									  $message .=				'<p>';
									  $message .=					'<br />Regards, <br /> Pairness Team<br />';
									  $message .=				'</p>';
									  $message .=				'<p>';
									  $message .=					'<a href="http://www.pairness.com">http://www.pairness.com</a>';
									  $message .=				'</p>';
									  $message .=			'</td>';
									  $message .=		  '</tr>';
									  $message .=		'</table>';
									  $message .=	'</td>';
									  $message .=    '<td valign="top">';
									  $message .=	'</td>';
									  $message .=  '</tr>';
									  $message .='</table>';
									send_email($email,'Welcome to Pairness!',$message);
									$arr = array('s' => 1,'v'=>'Please check your email for further instructions');
									echo json_encode($arr);
								}
								else
								{
									$arr = array('s' => 0,'e'=>'There was a problem. We have dispatched monkeys to fix it');
									echo json_encode($arr);
								}
					
							}
							else
							{
								$arr = array('s' => 0,'e'=>'Username is already taken.');
								echo json_encode($arr);
							}
						}
						else
						{
							$arr = array('s' => 0,'e'=>'Email address is already in use.');
							echo json_encode($arr);
						}
				}
				else
				{	
					
					if($chkname && $name!='')
					{		
						$arr = array('s' => 0,'e'=>'Name Must be characters');
						echo json_encode($arr);		
					}
					elseif($chkemail && $email!='')
					{
						$arr = array('s' => 0,'e'=>'Email ID is not valid');
						echo json_encode($arr);	
					}
					else
					{
					$arr = array('s' => 0,'e'=>'Please fill all fields');
					echo json_encode($arr);
					}
				}
				
			}
			else
			{
				$arr = array('s' => 0,'e'=>'Only Post requests are accepted');
				echo json_encode($arr);
			}
				
?>