<?php
include('./includes/functions.php');
$id = $_REQUEST['id'];

$q = $mysqli->query("SELECT * from pairness_family WHERE id = '$id'");
	
	while($qrow= $q->fetch_object())
	  {	
		  $email = $qrow->familyemailaddress;
		  	  
	  }
	
	  
$p = $mysqli->query("SELECT * from login WHERE email LIKE '$email'");
		
				  while($prow= $p->fetch_object())
				{	
				
					$name = $prow->username;
				    $toiduser = $prow->uid;
				}
				
				
$message_id = $_REQUEST['recordid'];

?>
<div class="close-wrapper"><a href="#"><img src="./assets/css/img/cross.png"></a></div>
	<div class="inner-login">
    
		<?php
		
		$p = $mysqli->query("SELECT * from pairness_message WHERE messageid = '$message_id'");
?>
		<table border="1" width="600px" height="auto" cellspacing="0" cellpadding="5" >
<tr style=" background:#CCC;"><th>Tittle</th><th>Detail</th><th>From</th></tr>
	
    	 <?php while($row= $p->fetch_object())
				{ ?>
					<tr>
					<?php
					$messageid = $row->messageid;
					$fromuser = $row->fromuser;
					$fromid = $row->fromid;
					$toid = $row->toid;
					$touser = $row->touser;
					$messagetitle = $row->messagetitle;
					$messagecontent = $row->messagecontent;
					$messageread = $row->messageread; ?>
					
					<td><?php echo $messagetitle ?></td>
				<td><?php echo $messagecontent ?></td>
				<td><?php echo $fromuser ?></td>
					
				 <?php } // While ends here
				 
				 $update = $mysqli->query("UPDATE pairness_message SET messageread = 1 WHERE messageid = '$message_id'");
				 
				 ?>
				<br>
				</tr> 
   
</table><br>



<form action="inbox.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="fromid" value="<?php echo $fromid ?>"  />
<input type="hidden" name="msgtitle" value="<?php echo $messagetitle ?>"  />
<input type="hidden" name="toid" value="<?php echo $toid ?>"  />
<input type="hidden" name="touser" value="<?php echo $touser ?>"  />
<input type="hidden" name="fromuser" value="<?php echo $fromuser ?>"  />
<input type="hidden" name="chk" value="1"  />
<textarea rows="4" cols="30" name="reply_message" id="reply_message" placeholder="Enter Your Reply"></textarea><br><br>
<input name="reply" id="reply" type="image" src="./assets/css/img/button-reply.png">
</form>

<br>
<?php
//echo $toid.'/'.$touser.'/'.$fromuser.'/'.$fromid.'/'.$messagetitle.'/'.$reply_message;

?>
    </div>