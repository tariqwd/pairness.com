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
				    $toid = $prow->uid;
				}
				//echo $forid;

?>
<div class="close-wrapper"><a href="#"><img src="./assets/css/img/cross.png"></a></div>
<h1>Send Email to <?php echo $name; ?> </h1>


<table width="510" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
  
    <td><table width="510" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15">&nbsp;</td>
        <td><table width="482" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td bgcolor=""><table width="482" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>
            <form id="email-form" name="email-form" action="" method="post" enctype="multipart/form-data">
            <table width="482" border="0" cellspacing="0" cellpadding="0">
              <tr>
              
                <td>
                
                  <tr>
                    <td width="20" align="right" class="formtext">Subject</td>
                    <td><select name="email_subject" id="email_subject" required> 
                        <option value="Hi, There!" >Hi, There!</option>
                        <option value="I like your profile">I like your profile</option>
                        <option value="Nice Photo!">Nice Photo!</option>
                        <option value="I'm intersted in you">I'm intersted in you</option>
                        <option value="You caught my eye">You caught my eye</option>
                        <option value="You are cute">You are cute</option>
                        <option value="Love at first sight">Love at first sight</option>
                        </select>
    			  </td>
                  </tr>
                  <tr>
                    <td width="90" align="right" class="formtext">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="formtext" align="right"></td>
                    <td><textarea title="enter email here" name="msg" style="width:250px; height:250px;" /> <!--<input type="text" required="required" class="ipt" placeholder="Enter Your Email" name="email" id="email" />--></td>
                  </tr>
                  <tr>
                    <td width="90" align="right" class="formtext">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="formtext" align="right"></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td class="formtext" align="right"></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td class="formtext" align="right"></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td><input type="hidden" name="isfamily" id="isfamily" value="1"/></td>
                    <td><input type="hidden" name="toid" id="toid" value="<?php echo $toid; ?>"/>
                    	<input type="hidden" name="touser" id="touser" value="<?php echo $name; ?>"/>
                    
                    </td>
                  </tr>
                  <tr>
                  <td></td>
                    <td align="center"><img src="./assets/css/img/btn_submit.png" class="email-submit" width="123" height="39" /></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                
                </td>             
                <td width="260"></td>
              </tr>
              
            </table>
            </form>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          
        </table></td>
        <td width="14">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
