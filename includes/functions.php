<?php
/*********************************************

	Copyright:	Cozmuler Pakistan
	File Name:	functions.php
	Package:	Pairness.com
	Author:		Rahber

*********************************************/



global $mysqli;


function startSession($timeout = 6000){ /* Start session and session cronning */
	global $mysqli;
	session_name('czid');
	session_set_cookie_params(0);
	session_start();

	if (isset($_SESSION['timeout_idle']) && $_SESSION['timeout_idle'] < time()) {

		$dp = session_id();
		$mysqli->query("DELETE FROM session WHERE sessionid='$dp'");
        session_destroy();
        session_start();
        session_regenerate_id();
        $_SESSION = array();
		
    }else{
	$_SESSION['timeout_idle'] = time() + $timeout;
	}
    

}

function update_lastpage($page){ /* Update user last page */
	global $mysqli;
	if($page!='logout.php' && check_login()){
	$id = $_SESSION['id'];
	if($mysqli->query("UPDATE login set lastpage='$page' where uid ='$id'")){
		return true;
	}
	else {
		return false;
	}
	return false;
	}

}

function takemehome(){ /* Redirection to home. */

redirect("home.php");

}

function update_session(){ /* Update session and update time so user is always with in 10 minutes of activity */
	if(check_login()){
	global $mysqli;
		$sep = $_SESSION['true'];
		$id = $_SESSION['id'];
		$t = time();
		if($mysqli->query("UPDATE session set t='$t' where (sessionid='$sep' and uid ='$id')")){
			return true;
		}
		else {
			return false;
		}
	}else{  return false;  }
}

function randomalpha($length){  /* Random string generator */
    $alphNums = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";        
    $newString = str_shuffle(str_repeat($alphNums, rand(1, $length))); 
    return substr($newString, rand(0,strlen($newString)-$length), $length); 
} 

function set_name($title){ /* Set name {{sname}} with the given variable */

	$pageContents = ob_get_contents (); // Get all the page's HTML into a string
	ob_end_clean (); // Wipe the buffer
	$title =  $title ." - ". get_config('sitename') ;
	echo str_replace ('{{sname}}', $title, $pageContents);
}

function remove_session(){/* Logout a user */
	global $mysqli;
	$ds = $_SESSION['true'];
	$mysqli->query("DELETE FROM session WHERE sessionid='$ds'");
	if(check_login()){
	add_log($_SESSION['id'],"User successfully logged out!");
	}
	
	session_unset();
	$_SESSION = array();
	
    session_destroy();
	startSession();
	session_regenerate_id();

}

function get_name($uid){ /* Get current user name */
	global $mysqli;
	$result = $mysqli->query("SELECT * FROM login where uid='$uid'");
	while($row = $result->fetch_object()){
		return $p = $row->username;
	}

}

function get_gender() { /* Get current user gender */
	
	
}

function update_config($name,$data){ /* Update Configurations */
	global $mysqli;
	if($mysqli->query("UPDATE config set value='$data' where name='$name'")){
		return true;
	}
	else {
		return false;
	}
}

function get_config($vvalue){  /*  Get values from configuration. Configuration to do.*/
	global $mysqli;
	$result = $mysqli->query("SELECT * FROM config where name='$vvalue'");
	while($row = $result->fetch_object()){
		return $p = $row->value;
	}
}


function redirect($add,$delay=0){ /* Redirection Script */
	//echo "window.setTimeout(function(){ window.location = '".$add."'; }, ".$delay.")";
	header("Refresh:". $delay .";url=". $add ); 
}


function onpage($ppg){ /* Returns true if you are on current page */
	if($ppg==pgname())
		return true;
	else
		return false;
}

function pgname(){ /* Current page full name without extension */
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$parts = $parts[count($parts) - 1];
	$parts = Explode('.', $parts);
	$pageName = $parts[0];
	return $pageName;
}

function fullpagename(){ /* Current page full name with exntension */
	$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$parts = $parts[count($parts) - 1];
	return $parts;

}

function set_var(&$result, $var, $type, $multibyte = false) /* Part of request_var function */
{
	settype($var, $type);
	$result = $var;

	if ($type == 'string')
	{
		$result = trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $result), ENT_COMPAT, 'UTF-8'));

		if (!empty($result))
		{
			// Make sure multibyte characters are wellformed
			if ($multibyte)
			{
				if (!preg_match('/^./u', $result))
				{
					$result = '';
				}
			}
			else
			{
				// no multibyte, allow only ASCII (0-127)
				$result = preg_replace('/[\x80-\xFF]/', '?', $result);
			}
		}

		//$result = (STRIP) ? stripslashes($result) : $result;
	}
}


function request_var($var_name, $default, $multibyte = false, $cookie = false) /* GET OR POST  Alternative. Always use it to avoid injection or hacking attempts  */
{
	if (!$cookie && isset($_COOKIE[$var_name]))
	{
		if (!isset($_GET[$var_name]) && !isset($_POST[$var_name]))
		{
			return (is_array($default)) ? array() : $default;
		}
		$_REQUEST[$var_name] = isset($_POST[$var_name]) ? $_POST[$var_name] : $_GET[$var_name];
	}

	$super_global = ($cookie) ? '_COOKIE' : '_REQUEST';
	if (!isset($GLOBALS[$super_global][$var_name]) || is_array($GLOBALS[$super_global][$var_name]) != is_array($default))
	{
		return (is_array($default)) ? array() : $default;
	}

	$var = $GLOBALS[$super_global][$var_name];
	if (!is_array($default))
	{
		$type = gettype($default);
	}
	else
	{
		list($key_type, $type) = each($default);
		$type = gettype($type);
		$key_type = gettype($key_type);
		if ($type == 'array')
		{
			reset($default);
			$default = current($default);
			list($sub_key_type, $sub_type) = each($default);
			$sub_type = gettype($sub_type);
			$sub_type = ($sub_type == 'array') ? 'NULL' : $sub_type;
			$sub_key_type = gettype($sub_key_type);
		}
	}

	if (is_array($var))
	{
		$_var = $var;
		$var = array();

		foreach ($_var as $k => $v)
		{
			set_var($k, $k, $key_type);
			if ($type == 'array' && is_array($v))
			{
				foreach ($v as $_k => $_v)
				{
					if (is_array($_v))
					{
						$_v = null;
					}
					set_var($_k, $_k, $sub_key_type, $multibyte);
					set_var($var[$k][$_k], $_v, $sub_type, $multibyte);
				}
			}
			else
			{
				if ($type == 'array' || is_array($v))
				{
					$v = null;
				}
				set_var($var[$k], $v, $type, $multibyte);
			}
		}
	}
	else
	{
		set_var($var, $var, $type, $multibyte);
	}

	return $var;
}



function short($string, $limit, $break=".", $pad="..."){ /* Cuts a given paragraph into small chunk with ... dots */
	if(strlen($string) <= $limit) return $string;
	if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	}
	return $string;
}
 
function isfamily(){  /* Check if the current user is a family member of bride groom himself/herself */
	global $mysqli;
	$u= $_SESSION['id'];
	$queryy = $mysqli->query("select * from login where uid='$u'");
	while ($row = $queryy->fetch_object()){
		$vf = $row->isfamily;
	}
	if($vf==1){
		return true;
	}else{
	 return false;
	}
	
}

function is_vf($email){ /* check if current email is verified or not */
	global $mysqli;
	$queryy = $mysqli->query("select * from login where (email='$email' || username='$email' )");
	while ($row = $queryy->fetch_object()){
	return $vf = $row->emailverification;
	}
}

function fstatus(){ /* Wether the user is family or birde groom himself  */
  if(isfamily()){ 
return "You are logged in as a family member";
 }else{
return "You are logged in as a bride or groom";
 } 
}



function encrypt_text($value) /* 256bit Rijndael encryption */
{
   if(!$value) return false;
 
   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 'SECURE_STRING_1', $value, MCRYPT_MODE_ECB, 'SECURE_STRING_2');
   return trim(base64_encode($crypttext));
}
 
function decrypt_text($value) /* Decryption Functions */
{ 
   if(!$value) return false;
 
   $crypttext = base64_decode($value);
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 'SECURE_STRING_1', $crypttext, MCRYPT_MODE_ECB, 'SECURE_STRING_2');
   return trim($decrypttext);
}

function user_image(){ /* Get currentuser logged in image */

global $uploadpath;
$e = $_SESSION['email'];

global $mysqli;
	$queryy = $mysqli->query("select * from pairness_family where (familyemailaddress ='$e')");
	while ($row = $queryy->fetch_object()){
		 return  $uploadpath.$row ->familyprofileimage;
	}
	return $uploadpath. "default.png";

}

function do_login($email,$password,$remember,$return){ /*  Do login*/
	session_regenerate_id();
	
	
	$return = remove_http($return);
	global $mysqli;
	if($email!='' || $password!=''){
	$queryy = $mysqli->query("select * from login where ((email='$email' || username='$email' )and password='$password' and emailverification='1')");
			
		if( ($queryy->num_rows)==1){
			while ($row = $queryy->fetch_object()){
				$ds = session_id();
				$_SESSION['id'] = $row ->uid;
				$_SESSION['email'] = $row ->email;
				$_SESSION['name'] = $row ->username;
				$_SESSION['privacylevel'] = $row ->privacylevel;
				$_SESSION['host'] = $_SERVER['HTTP_HOST'];
				$_SESSION['true'] = $ds;
				insert_ses($ds,$row ->uid);
				add_log($row ->uid,"User successfully logged in!");
				$arr = array('uid' => $row ->uid, 'sid' => $ds, 's' => 1,'r'=>$return);		
				return json_encode($arr);
			}
		}
		else{
			if(is_vf($email)==1){
		
				$arr = array('s' => 0,'e'=>'Invalid Credentials');
				return json_encode($arr);
			}
			else{
				$arr = array('s' => 0,'e'=>'Inactive account or Account doesn\'t not exist');
				return json_encode($arr);
			}				
		}}else{
		$arr = array('s' => 0,'e'=>'Please fill all fields');
				return json_encode($arr);
		
		}
}

function insert_ses($a,$b){ /* Insert current session in db */
	global $mysqli;
	$time = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	$ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$q = "INSERT INTO session (uid,sessionid,t,ip,ip2) VALUES ('$b','$a','$time','$ip','$ip2');";
	$mysqli->query($q);
}

function check_login(){ /*  Returns true or false that is a user is logged in or not */
	$ddd = session_id() ;global $mysqli;		
	if(isset($_SESSION['id'])){	
				
		$queryy = $mysqli->query("select * from session where (sessionid='$ddd')");
		$row_cnt = $queryy->num_rows;
	
		if($row_cnt==1){
			return true;
		}else{
			return false;
		}
	
	}
	else{
			
		return false;
	}
}

function remove_http($url) { /* Remove http or https from url */
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   return $url;
}

function getsubpage(){ /* Get Subpage  */

$currentFile = $_SERVER["PHP_SELF"];
	$parts = Explode('/', $currentFile);
	$parts = $parts[count($parts) - 1];
	$parts = Explode('.', $parts);
	$pageName = $parts[0];
	return $pageName;


}

function auth_check(){ /* Check if a user is logged in or not */
	$pg = pgname();
	if(!check_login() && $pg !='index'){
		redirect($sitepath.'index.php?return='.$pg);
	}else if(check_login() && $pg =='index'){
	redirect($sitepath.'home.php');
	}
}

function verify_id($email,$cs){ /* Verification of email*/
	global $mysqli;


	$query = $mysqli->query("select * from login where email='$email' and sc='$cs'");
		if($query->num_rows==1){
			$d =time();
			$finfo = $query ->fetch_assoc();
			$fm = $finfo['isfamily'];
			
			if($finfo['emailverification']==0){
				$quer = $mysqli->query("update login set verificationdate='$d',emailverification=1 where email='$email' and sc='$cs'");
				
				add_log($finfo['uid'],"User has successfully verified the email");
				
				$subject = "Your Email has been succsessfully verified";
				$body = "Yay this is the body of succesfuly verified email";
				send_email($email,$subject,$body);
				if($fm)
				{
					return "<div class='success'>Successfully Verified. Click <a href='complete_profile_g.php?finfo=".$fm."&view=".urlencode(encrypt_text($email))."&key=".urlencode(encrypt_text($cs))."'>here</a> for next step</div>";
				}
				else 
				{
					return "<div class='success'>Successfully Verified. Click <a href='complete_profile_s.php?finfo=".$fm."&view=".urlencode(encrypt_text($email))."&key=".urlencode(encrypt_text($cs))."'>here</a> for next step</div>";
				}
		

			}else{
				return "<div class='warning'>The Account is already verified!</div>";
			}
		}
		else{
			return "<div class='error'>Invalid Key or Email!</div>";
		}
}

/*function complete_secondstep($email,$cs){
	global $mysqli;

			return "<div class='error'>Invalid Key or Email!</div>";
		
}*/


function recover_cred($emailt,$emailr){ /* Recover credentials */
	global $mysqli;
	
	if ($emailt!='' && $emailr!='')
	{
		
		return "<div class='error'>Please Specify email address</div>";
		 	
	}
	elseif ($emailt=='' && $emailr=='')
	{
		
		return "<div class='error'>No email address found</div>";
	
	}
	else
	{  
		if($emailt)
		{
			$query = $mysqli->query("select * from login where email='$emailt'");
			
			if($query->num_rows==1){
				
			$finfo = $query ->fetch_assoc();
			$fm = $finfo['isfamily'];
			$user = $finfo['username'];
			$em = $finfo['email'];
			$pass = $finfo['password'];
			
			$subject = "Account Credentials";
			
			$message = '<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="border:solid 5px #025285;">';
								  $message .=  '<tr>';
								  $message .=    '<th colspan="2" scope="row" align="center"><span style="color:#025285;"><hr/></span></th>';
								  $message .=  '</tr>';
								  $message .= '<tr>';
								  $message .=    '<td align="left" valign="top">';
								  $message .=		'<table width="400" border="0" cellspacing="0" cellpadding="0" style="padding-top:20px;">';
								  $message .=		  '<tr>';
								  $message .=			'<th scope="row"><h2 style="color:#025285; font-family:Arial, Helvetica, sans-serif; text-align:center;">Pairness Account Credentials</h2></th>';
								  $message .=		  '</tr>';
								  $message .=		  '<tr>';
								  $message .=		  	'<td>&nbsp;</td>';
								  $message .=		  '</tr>';
								  $message .=		  '<tr>';
								  $message .=			'<td style="color:#464646; padding-left:15px; font-family:Arial, Helvetica, sans-serif;">';
								  $message .=				'<span style=""><strong>Username: </strong>'.$user.'</span> <br />';
								  $message .=				'<p><br /></p>';
								  $message .=				'<span style=""><strong>Email Address: </strong>'.$em.'</span> <br />';
								  $message .=				'<p><br /></p>';
								  $message .=				'<span style=""><strong>Password: </strong>'.$pass.'</span>';
								  $message .=				'<p><br /> <br /></p>';
								  $message .=				'<p style="font-size:13px;">';
								  $message .=					'Please don&#39;t share this information.<br />';
								  $message .=				'</p>';
								  $message .=				'<p style="font-size:13px;">';
								  $message .=					'<a href="http://www.cozmuler.com/projects/dev/pairness.com/verify.php">Click Here</a> to Find your match<br />';
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
			
		    send_email($emailt,$subject,$message);
		    return "<div class='success'>Please check your email for further instructions</div>";	
			}
			
			else
			{
				 return "<div class='warning'>Email Address Not Registered with us</div>";
			}
				
		}
		
		if ($emailr)
		{
			
			 return "<div class='warning'>Still working on that!</div>";
		}
	}
}


function add_log($uid,$action){ /* Add log values*/
	global $mysqli;
	$time = time();
	$q = "INSERT INTO log (uid,action,logtime) VALUES ('$uid','$action','$time');";
	$mysqli->query($q);
}

function cron_session(){ /* Check if the session that exist in database is older than 10 minutes*/
	global $mysqli;
	$t = time();
	$queryy =  $mysqli->query("Select * from session");
		while ($row = $queryy->fetch_object()){
			$op = $row->sessionid;
			if((($t) - ($row->t)) > 6000){
			$mysqli->query("DELETE FROM session WHERE sessionid='$op'");
			}
		}
}

function send_email($email,$subject,$body,$bccallow=0,$bccemail=''){ /* Send email function */
	global $contactemail;
    $to = $email; 
    $from = $contactemail; 

    //$headers = "MIME-Version: 1.0rn"; 
    //$headers .= "Content-type: text/html; charset=iso-8859-1rn"; 
    //$headers  .= "From: $from\r\n"; 
	
	$headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= "From: ".$from. "\r\n";

   if($bccallow==1){
    $headers .= "Bcc: $bccemail"; 
	}
 
    // now lets send the email. 
    if(mail($to, $subject, $body, $headers)){
		return 1;	
	}else{
		return 0;
	}



}

function cache_bottom(){ /* Cache bottom initlaizer */
global $enablecache;
if($enablecache==1 && check_login() &&  pgname()!='search'){
	global $cachefile;
	// Cache the contents to a file
	$cached = fopen($cachefile, 'w');
	fwrite($cached, ob_get_contents());
	fclose($cached);
	ob_end_flush(); // Send the output to the browser
	}
}

function purgecache(){ /* Delete all cache files*/
	$c=0;

	$files = glob('../cache/*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); 
				$c++;// delete file
		}

			$arr = array('s' => 1,'v'=>$c .' file(s) was/were purged');
				return json_encode($arr);
	

}

function getage($dob){ /* Should return correct date of birth by calculating year and months from DOB */
	return 21;
}

function getemail($id){ /* Get current user email*/
	global $mysqli;
	$q = $mysqli->query("select * from login where uid = '$id'");
	while($row= $q->fetch_object()){	
		return $row->email;	
	}
}

function cache_top(){ /* Start of Cache Header */
	global $enablecache;
	if($enablecache==1 && check_login() && pgname()!='search'){
		global $cachefile,$sitepath,$view;
		$urlp = $_SERVER["SCRIPT_NAME"];
		$break = Explode('/', $urlp);
		$filep = $break[count($break) - 1];
		if(check_login()){
			$idp= $_SESSION['id'];
		}else{
			$idp = 0;
		}
		if(pgname()=='profile'){
			if($view!='0'){
				$idp = $idp .'-'. ($view);
			}else{
				$idp = $idp .'-'.$idp;
			}
		}
		$cachefile = './cache/cached-'.$idp.'-'.substr_replace($filep ,"",-4).'.html';
		$cachetime = 18000;

// Serve from the cache if it is younger than $cachetime
		if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile) ) {
			echo "<!-- \n\tCached  Copy, generated ".date('H:i', filemtime($cachefile))." \n\t (c) Cozmuler 2013 \n\t Author: rahber \n-->\n";
			include($cachefile);
		exit;
		}
	ob_start();
	}
}

function getvars(){ /* Initilaize Variables to get default variables using get or post*/
global $ret,$f,$view,$skey;
	$ret = request_var('return','');  
	if(pgname()=='candidate'){$f= request_var('f',0);  }
	if(pgname()=='profile' || pgname()=='complete_profile_s' || pgname()=='complete_profile_g'){$view = request_var('view','0'); 
	if($view=='0'){
	}else{
	 $view = urldecode(decrypt_text($view));
	}
	}
	if( pgname()=='complete_profile_s' || pgname()=='complete_profile_g'){$skey = request_var('key',''); 
	if($skey==''){
	}else{
	 $skey = urldecode(decrypt_text($skey));
	}
	}
}

function adddash(){ /* Add Dash */
	return ' - ';
}

function getfeature($value){ /* Return the feature id name on the base of id*/
	global $mysqli;
	$result = $mysqli->query("SELECT * FROM pairness_features where id='$value'");
	while($row = $result->fetch_object()){
		return $p = $row->name;
	}
}

function addbreak(){ /* Adds a line break*/
	return "<br />";
}

function fixheight(){ /* To-do: Should add aphostophy to show proper height */
	return "5'11";
}

/////////////////////////////////////////////////////////////////////////////////

function age($dob) { /*calculate age on the basis of dob*/

    list($y,$m,$d) = explode('-', $dob);
    
    if (($m = (date('m') - $m)) < 0) {
        $y++;
    } elseif ($m == 0 && date('d') - $d < 0) {
        $y++;
    }
    
    return date('Y') - $y;
    
}

function family_feature($family_val){ // select features name for drop down list
	global $mysqli;
	$list = $mysqli->query("SELECT name from pairness_features WHERE id = '$family_val'");
	$list = $list->fetch_object();
	echo  $list->name;		
}

/*function candidate_feature($candidate_val){ // select features name for drop down list
	global $mysqli;
	$list = $mysqli->query("SELECT name from pairness_features WHERE id = '$candidate_val'");
	$list = $list->fetch_object();
	echo $list->name;		
}*/

function family_search($seekinggender,$seekingminage,$seekingmaxage,$seekingcountry,$seekingimg,$start,$limit){ //search query
	
	global $mysqli;
	$search_query = "";
		$search_query.= "SELECT * FROM pairness_family WHERE ";
		if($seekingcountry > 0)
		{
			$search_query.= "familycountry LIKE '$seekingcountry' AND "; 	
		}
		if($seekingminage > 0 && $seekingmaxage > 0)
		{
			$search_query.= "familyage BETWEEN '$seekingminage' and '$seekingmaxage' AND ";
		}
		if($seekingimg > 0)
		{
			$search_query.= "familyprofileimage > '' and familyprofileimage !='default.png' AND ";
		}
		
		$search_query.="familygender LIKE '$seekinggender' ORDER BY id ASC LIMIT $start, $limit;";
	
		//echo $search_query;
		$q = $mysqli->query($search_query);	
		return $q;
	
}

function family_search_numofrecords($seekinggender,$seekingminage,$seekingmaxage,$seekingcountry,$seekingimg){ //search query
	
	global $mysqli;
	$search_query = "";
		$search_query.= "SELECT * FROM pairness_family WHERE ";
		if($seekingcountry > 0)
		{
			$search_query.= "familycountry LIKE '$seekingcountry' AND "; 	
		}
		if($seekingminage > 0 && $seekingmaxage > 0)
		{
			$search_query.= "familyage BETWEEN '$seekingminage' and '$seekingmaxage' AND ";
		}
		if($seekingimg > 0)
		{
			$search_query.= "familyprofileimage > '' and familyprofileimage !='default.png' AND ";
		}
		
		$search_query.="familygender LIKE '$seekinggender';";
		
		//echo $search_query;
		$query = $mysqli->query($search_query);
		$q = $query->num_rows;	
		return $q;
	
}

function match_search($pgender,$pminage,$pmaxage,$pcountry,$pstate,$pcity,$phaircolor,$phairtype,$peyecolor,$peyewear,$pheight,$pweight,$pbodytype,$pappearance,$pfacialhair,$pphysicalstatus,$pmaritalstatus,$phavechildrens,$pvalues,$plivingsituation,$pfoodhabits,$peducation,$poccupation,$prelocate,$preligion,$pbornreverted,$preligiousvalues,$pattendreligiousservices,$pmothertongue,$pethnicity,$pnationality,$pplaceofbirth,$planguagesspoken,$pgetmarried,$pwantmorechildrens,$pdowry,$start,$limit){ //match query
	global $mysqli;
	$search_query = "";
	$search_query.= "SELECT * FROM pairness_family WHERE ";
	if($pcountry > 0)
	{
		$search_query.= "familycountry LIKE '$pcountry' AND "; 	
	}
	if($pminage > 0 && $pmaxage > 0)
	{
		$search_query.= "familyage BETWEEN '$pminage' and '$pmaxage' AND ";
	}
	if($pstate > 0)
	{
		$search_query.= "familystate LIKE '$pstate' AND ";
	}
	if($pcity>0)
	{
		$search_query.= "familycity LIKE '$pcity' AND ";	
	}
	if($phaircolor>0)
	{
		$search_query.= "familyhaircolor LIKE '$phaircolor' AND ";	
	}
	if($phairtype>0)
	{
		$search_query.= "familyhairtype LIKE '$phairtype' AND ";	
	}
	if($phaircolor>0)
	{
		$search_query.= "familyhaircolor LIKE '$phaircolor' AND ";	
	}
	if($peyecolor>0)
	{
		$search_query.= "familyeyecolor LIKE '$peyecolor' AND ";	
	}
	if($peyewear>0)
	{
		$search_query.= "familyeyewear LIKE '$peyewear' AND ";	
	}
	if($pheight>0)
	{
		$search_query.= "familyheight LIKE '$pheight' AND ";	
	}
	if($pweight>0)
	{
		$search_query.= "familyweight LIKE '$pweight' AND ";	
	}
	if($pbodytype>0)
	{
		$search_query.= "familybodytype LIKE '$pbodytype' AND ";	
	}
	if($pappearance>0)
	{
		$search_query.= "familyappearance LIKE '$pappearance' AND ";	
	}
	if($pfacialhair>0)
	{
		$search_query.= "familyfacialhair LIKE '$pfacialhair' AND ";	
	}
	if($pphysicalstatus>0)
	{
		$search_query.= "familyphysicalstatus LIKE '$pphysicalstatus' AND ";	
	}
	if($pmaritalstatus>0)
	{
		$search_query.= "familymaritalstatus LIKE '$pmaritalstatus' AND ";	
	}
	if($phavechildrens>0)
	{
		$search_query.= "familyhavechildrens LIKE '$phavechildrens' AND ";	
	}
	if($pvalues>0)
	{
		$search_query.= "familyvalues LIKE '$pvalues' AND ";	
	}
	if($plivingsituation>0)
	{
		$search_query.= "familylivingsituation LIKE '$plivingsituation' AND ";	
	}
	if($pfoodhabits>0)
	{
		$search_query.= "familyfoodhabits LIKE '$pfoodhabits' AND ";	
	}
	if($peducation>0)
	{
		$search_query.= "familyeducation LIKE '$peducation' AND ";	
	}
	if($poccupation>0)
	{
		$search_query.= "familyoccupation LIKE '$poccupation' AND ";	
	}
	if($prelocate>0)
	{
		$search_query.= "familyrelocate LIKE '$prelocate' AND ";	
	}
	if($preligion>0)
	{
		$search_query.= "familyreligion LIKE '$preligion' AND ";	
	}
	if($pbornreverted>0)
	{
		$search_query.= "familybornreverted LIKE '$pbornreverted' AND ";	
	}
	if($preligiousvalues>0)
	{
		$search_query.= "familyreligiousvalues LIKE '$preligiousvalues' AND ";	
	}
	if($pattendreligiousservices>0)
	{
		$search_query.= "familyattendreligiousservices LIKE '$pattendreligiousservices' AND ";	
	}
	if($pmothertongue>0)
	{
		$search_query.= "familymothertongue LIKE '$pmothertongue' AND ";	
	}
	if($pethnicity>0)
	{
		$search_query.= "familyethnicity LIKE '$pethnicity' AND ";	
	}
	if($pnationality>0)
	{
		$search_query.= "familynationality LIKE '$pnationality' AND ";	
	}
	if($pplaceofbirth>0)
	{
		$search_query.= "familyplaceofbirth LIKE '$pplaceofbirth' AND ";	
	}
	if($planguagesspoken>0)
	{
		$search_query.= "familylanguagesspoken LIKE '$planguagesspoken' AND ";	
	}
	if($pgetmarried>0)
	{
		$search_query.= "familygetmarried LIKE '$pgetmarried' AND ";	
	}
	if($pwantmorechildrens>0)
	{
		$search_query.= "familywantmorechildrens LIKE '$pwantmorechildrens' AND ";	
	}
	if($pdowry>0)
	{
		$search_query.= "familydowry LIKE '$pdowry' AND ";	
	}
	if($pgender)
	{
	$search_query.="familygender LIKE '$pgender' ";
	}
	
	$search_query.="ORDER BY id ASC LIMIT $start,$limit;"; 
	//echo $search_query;
	$q = $mysqli->query($search_query);	
	return $q;	
	
}

function match_search_numofrecords($pgender,$pminage,$pmaxage,$pcountry,$pstate,$pcity,$phaircolor,$phairtype,$peyecolor,$peyewear,$pheight,$pweight,$pbodytype,$pappearance,$pfacialhair,$pphysicalstatus,$pmaritalstatus,$phavechildrens,$pvalues,$plivingsituation,$pfoodhabits,$peducation,$poccupation,$prelocate,$preligion,$pbornreverted,$preligiousvalues,$pattendreligiousservices,$pmothertongue,$pethnicity,$pnationality,$pplaceofbirth,$planguagesspoken,$pgetmarried,$pwantmorechildrens,$pdowry){ //match query
	global $mysqli;
	$search_query = "";
	$search_query.= "SELECT * FROM pairness_family WHERE ";
	if($pcountry > 0)
	{
		$search_query.= "familycountry LIKE '$pcountry' AND "; 	
	}
	if($pminage > 0 && $pmaxage > 0)
	{
		$search_query.= "familyage BETWEEN '$pminage' and '$pmaxage' AND ";
	}
	if($pstate > 0)
	{
		$search_query.= "familystate LIKE '$pstate' AND ";
	}
	if($pcity>0)
	{
		$search_query.= "familycity LIKE '$pcity' AND ";	
	}
	if($phaircolor>0)
	{
		$search_query.= "familyhaircolor LIKE '$phaircolor' AND ";	
	}
	if($phairtype>0)
	{
		$search_query.= "familyhairtype LIKE '$phairtype' AND ";	
	}
	if($phaircolor>0)
	{
		$search_query.= "familyhaircolor LIKE '$phaircolor' AND ";	
	}
	if($peyecolor>0)
	{
		$search_query.= "familyeyecolor LIKE '$peyecolor' AND ";	
	}
	if($peyewear>0)
	{
		$search_query.= "familyeyewear LIKE '$peyewear' AND ";	
	}
	if($pheight>0)
	{
		$search_query.= "familyheight LIKE '$pheight' AND ";	
	}
	if($pweight>0)
	{
		$search_query.= "familyweight LIKE '$pweight' AND ";	
	}
	if($pbodytype>0)
	{
		$search_query.= "familybodytype LIKE '$pbodytype' AND ";	
	}
	if($pappearance>0)
	{
		$search_query.= "familyappearance LIKE '$pappearance' AND ";	
	}
	if($pfacialhair>0)
	{
		$search_query.= "familyfacialhair LIKE '$pfacialhair' AND ";	
	}
	if($pphysicalstatus>0)
	{
		$search_query.= "familyphysicalstatus LIKE '$pphysicalstatus' AND ";	
	}
	if($pmaritalstatus>0)
	{
		$search_query.= "familymaritalstatus LIKE '$pmaritalstatus' AND ";	
	}
	if($phavechildrens>0)
	{
		$search_query.= "familyhavechildrens LIKE '$phavechildrens' AND ";	
	}
	if($pvalues>0)
	{
		$search_query.= "familyvalues LIKE '$pvalues' AND ";	
	}
	if($plivingsituation>0)
	{
		$search_query.= "familylivingsituation LIKE '$plivingsituation' AND ";	
	}
	if($pfoodhabits>0)
	{
		$search_query.= "familyfoodhabits LIKE '$pfoodhabits' AND ";	
	}
	if($peducation>0)
	{
		$search_query.= "familyeducation LIKE '$peducation' AND ";	
	}
	if($poccupation>0)
	{
		$search_query.= "familyoccupation LIKE '$poccupation' AND ";	
	}
	if($prelocate>0)
	{
		$search_query.= "familyrelocate LIKE '$prelocate' AND ";	
	}
	if($preligion>0)
	{
		$search_query.= "familyreligion LIKE '$preligion' AND ";	
	}
	if($pbornreverted>0)
	{
		$search_query.= "familybornreverted LIKE '$pbornreverted' AND ";	
	}
	if($preligiousvalues>0)
	{
		$search_query.= "familyreligiousvalues LIKE '$preligiousvalues' AND ";	
	}
	if($pattendreligiousservices>0)
	{
		$search_query.= "familyattendreligiousservices LIKE '$pattendreligiousservices' AND ";	
	}
	if($pmothertongue>0)
	{
		$search_query.= "familymothertongue LIKE '$pmothertongue' AND ";	
	}
	if($pethnicity>0)
	{
		$search_query.= "familyethnicity LIKE '$pethnicity' AND ";	
	}
	if($pnationality>0)
	{
		$search_query.= "familynationality LIKE '$pnationality' AND ";	
	}
	if($pplaceofbirth>0)
	{
		$search_query.= "familyplaceofbirth LIKE '$pplaceofbirth' AND ";	
	}
	if($planguagesspoken>0)
	{
		$search_query.= "familylanguagesspoken LIKE '$planguagesspoken' AND ";	
	}
	if($pgetmarried>0)
	{
		$search_query.= "familygetmarried LIKE '$pgetmarried' AND ";	
	}
	if($pwantmorechildrens>0)
	{
		$search_query.= "familywantmorechildrens LIKE '$pwantmorechildrens' AND ";	
	}
	if($pdowry>0)
	{
		$search_query.= "familydowry LIKE '$pdowry' AND ";	
	}
	if($pgender)
	{
	$search_query.="familygender LIKE '$pgender';";
	}
	
	
	$query = $mysqli->query($search_query);
	$q = $query->num_rows;	
	return $q;
	
}



function getfeatures($featureid){ //get feature name against feature id

	global $mysqli;
	$p = $mysqli->query("SELECT * from pairness_features WHERE id = '$featureid'");
	
            	while($row = $p->fetch_object()){
					$val = $row->name;
                //echo  "<option value='". $row->id ."'>". $row->name ."</option>";
			}	
	
	return $val;
}

function pagination($per_page, $page, $url, $total){    // Pagination module

	$adjacents = "2";

	$page = ($page == 0 ? 1 : $page); 
	$start = ($page - 1) * $per_page; 

	$prev = $page - 1; 
	$next = $page + 1;
	$lastpage = ceil($total/$per_page);
	$lpm1 = $lastpage - 1;

	$pagination = "";
	if($lastpage > 1)
	{ 
		$pagination .= "<ul class='pagination'>";
		$pagination .= "<li class='details'>Page $page of $lastpage</li>";
	if ($lastpage < 7 + ($adjacents * 2))
	{ 
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $page)
				$pagination.= "<li><a class='current'>$counter</a></li>";
			else
				$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>"; 
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2))
	{
		if($page < 1 + ($adjacents * 2)) 
		{
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><a class='current'>$counter</a></li>";
				else
					$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>"; 
			}
			$pagination.= "<li class='dot'>...</li>";
			$pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
			$pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>"; 
		}
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
			$pagination.= "<li><a href='{$url}1'>1</a></li>";
			$pagination.= "<li><a href='{$url}2'>2</a></li>";
			$pagination.= "<li class='dot'>...</li>";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><a class='current'>$counter</a></li>";
				else
					$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>"; 
			}
			$pagination.= "<li class='dot'>..</li>";
			$pagination.= "<li><a href='{$url}$lpm1'>$lpm1</a></li>";
			$pagination.= "<li><a href='{$url}$lastpage'>$lastpage</a></li>"; 
		}
		else
		{
			$pagination.= "<li><a href='{$url}1'>1</a></li>";
			$pagination.= "<li><a href='{$url}2'>2</a></li>";
			$pagination.= "<li class='dot'>..</li>";
			for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li><a class='current'>$counter</a></li>";
				else
					$pagination.= "<li><a href='{$url}$counter'>$counter</a></li>"; 
			}
		}
	}

	if ($page < $counter - 1)
	{
		$pagination.= "<li><a href='{$url}$next'>Next</a></li>";
		// $pagination.= "<li><a href='{$url}$lastpage'>Last</a></li>";
	}
	else{
		//$pagination.= "<li><a class='current'>Next</a></li>";
		// $pagination.= "<li><a class='current'>Last</a></li>";
	}
	$pagination.= "</ul>\n"; 
	} 
	return $pagination;
} 

function getprofilelevel($em){
	
	global $mysqli;
	$count=0;
	//echo $em;
	$p = $mysqli->query("SELECT * from pairness_family WHERE familyemailaddress = '$em'");
	
            	while($row = $p->fetch_object()){
					//echo "m in";
					
					if ($row->familyseekinggender==''){$count++;}
					if ($row->familydateofbirth==0){$count++;}
					if ($row->familycountry==0){$count++;}
					if ($row->familystate==''){$count++;}
					if ($row->familycity==''){$count++;}
					if ($row->familyterms==''){$count++;}
					if ($row->familyfirstname==''){$count++;}
					if ($row->familylastname==''){$count++;}
					if ($row->familyphone==''){$count++;}
					if ($row->familycell==''){$count++;}
					if ($row->familyprofileimage==''){$count++;}
					if ($row->familyhaircolor==0){$count++;}
					if ($row->familyhairtype==0){$count++;}
					if ($row->familyeyecolor==0){$count++;}
					if ($row->familyeyewear==0){$count++;}
					if ($row->familyheight==0){$count++;}
					if ($row->familyweight==0){$count++;}
					if ($row->familyappearance==0){$count++;}
					if ($row->familyfacialhair==0){$count++;}
					if ($row->familyphysicalstatus==0){$count++;}
					if ($row->familymaritalstatus==0){$count++;}
					if ($row->familyhavechildrens==0){$count++;}
					if ($row->familyvalues==0){$count++;}
					if ($row->familylivingsituation==0){$count++;}
					if ($row->familylikes==''){$count++;}
					if ($row->familydislikes==''){$count++;}
					if ($row->familyzodiachormony==''){$count++;}
					if ($row->familyfoodhabits==0){$count++;}
					if ($row->familyeducation==0){$count++;}
					if ($row->familyoccupation==0){$count++;}
					if ($row->familyrelocate==0){$count++;}
					if ($row->familyreligion==0){$count++;}
					if ($row->familybornreverted==0){$count++;}
					if ($row->familyreligiousvalues==0){$count++;}
					if ($row->familyattendreligiousservices==0){$count++;}
					if ($row->familymothertongue==0){$count++;}
					if ($row->familyethnicity==0){$count++;}
					if ($row->familycast==''){$count++;}
					if ($row->familynationality==0){$count++;}
					if ($row->familyplaceofbirth==0){$count++;}
					if ($row->familylanguagesspoken==0){$count++;}
					if ($row->familyambition==''){$count++;}
					if ($row->familyhobbies==''){$count++;}
					if ($row->familydreams==''){$count++;}
					if ($row->familygetmarried==0){$count++;}
					if ($row->familywantmorechildrens==0){$count++;}
					if ($row->familydowry==0){$count++;}
					if ($row->familycnicnumber==0){$count++;}
					if ($row->familycnicimage==''){$count++;}
				}	
	
	$tskill = 49; 
	$skillnotcompleted = $tskill - $count;
	$val = ($skillnotcompleted / $tskill) * 100; 

	return round($val);
	
	
	
}

/////////////////////////////////////////////

function start_app(){ /* Initialize Different Variables */
	global $mysqli,$enablecache,$purgepage,$membershippage,$indexpage,$candidatepage,$uploadpath,$matchpage,$sitepath,$contactemail,$explorepage,$inboxpage,$homepage,$accountpage,$searchpage,$logoutpage,$photospage,$settingspage,$profilepage,$viewprofilepage, $editpreferencespage,$addaccount,$editprofilepage;
	// WebHost setting 
	  
	
	/*$mysqli = new mysqli("68.178.216.14", "pairnessdb", "Pairness#113", "pairnessdb");
	//$contactemail = "rahber@cozmuler.com";
	$contactemail = "we@cozmuler.com";
	$sitepath ="http://cozmuler.com/projects/dev/pairness.com/";*/
	
	
	
	// Localhost setting
	
	$mysqli = new mysqli("localhost", "root", "", "pairness");
	$contactemail = "we@cozmuler.com";
	$sitepath ="http://localhost/pairness.com/";
	
	$enablecache = 0;
	
	$purgepage = "purge.php";
	$indexpage = "index.php";
	$searchpage = "search.php";
	$accountpage = "account.php";
	$homepage = "home.php";
	$inboxpage ="inbox.php";
	$explorepage = "search.php?action=explore";
	$logoutpage = "logout.php";
	$photospage = "photos.php";
	$settingspage = "settings.php";
	$profilepage = "profile.php";
	$viewprofilepage = "view_profile.php";
	$editpreferencespage =  "editpreferences.php";
	$membershippage ="membership.php";
	$candidatepage ="candidate.php";
	$matchpage = "search.php?action=match";
	$addaccount = "addact.php";
	$uploadpath = $sitepath. "/upload_images/";
	$editprofilepage = "editprofile.php";
	
	error_reporting(0);
	startSession();
	update_session();
	cron_session();
	update_lastpage("$_SERVER[REQUEST_URI]");
	getvars();
	cache_top();
	


}

start_app();


?>