<?php	
	
include('../includes/functions.php');
if($_POST){				
				//-----------image1 uploading code here---------------------------------

$profile_picture = $_FILES['profile_picture']['name'];
//echo property_image."<br>";
if ($_FILES['profile_picture']['error'] == 0) 
{
  // check for file format/extension
  $profile_picture = basename ($_FILES['profile_picture']['name']);
    if(($_FILES['profile_picture']['type'] == "image/gif")
        || ($_FILES['profile_picture']['type'] == "image/jpeg")
        || ($_FILES['profile_picture']['type'] == "image/pjpeg")
		|| ($_FILES['profile_picture']['type'] == "image/png")
		|| ($_FILES['profile_picture']['type'] == "image/jpg")
		|| ($_FILES['profile_picture']['type'] == "image/bmp"))

  {
		    //get the path where file is to be saved
			//$filepath=dirname (__FILE__).'/images/';
			$new_file_name = 'upload_images/'.$profile_picture;
			//echo $new_file_name;
			//checking for file name duplication on server
	     if ( ! file_exists ( $new_file_name ) )
	   {
			   //move uploaded file to permanent place on server
		      if ( ( move_uploaded_file ( $_FILES ['profile_picture']['tmp_name'] , $new_file_name) ) )
		   {
		          $profile_picture = $profile_picture;
			      //echo 'File data has been Successfully saved as '.$new_file_name;
		   }
		      else
		   {
			      //echo 'Error uploading file </br>';      
		   }
	    }	   
	      else
		   {
		          //echo 'Error: file '.$_FILES['image_upload']['name'].' Already Exists </br>';
		   }
    }
  	else
	{
		       //echo 'Error: files with formats jpg , jpeg , png , gif , bmp </br>';                       
    } 
	}
else
{
	     //echo "Thank you:No file uploaded<br>";
	}
	
	//-----------image1 uploading code Ends here---------------------------------
	
	
	//-----------image2 uploading code here---------------------------------

$family_image = $_FILES['family_image']['name'];
//echo property_image."<br>";
if ($_FILES['family_image']['error'] == 0) 
{
  // check for file format/extension
  $filename_two = basename ($_FILES['family_image']['name']);
    if(($_FILES['family_image']['type'] == "image/gif")
        || ($_FILES['family_image']['type'] == "image/jpeg")
        || ($_FILES['family_image']['type'] == "image/pjpeg")
		|| ($_FILES['family_image']['type'] == "image/png")
		|| ($_FILES['family_image']['type'] == "image/jpg")
		|| ($_FILES['family_image']['type'] == "image/bmp"))

  {
		    //get the path where file is to be saved
			//$filepath=dirname (__FILE__).'/images/';
			$new_file_name = 'upload_images/'.$filename_two;
			//echo $new_file_name;
			//checking for file name duplication on server
	     if ( ! file_exists ( $new_file_name ) )
	   {
			   //move uploaded file to permanent place on server
		      if ( ( move_uploaded_file ( $_FILES ['family_image']['tmp_name'] , $new_file_name) ) )
		   {
		          $family_image = $filename_two;
			      //echo 'File data has been Successfully saved as '.$new_file_name;
		   }
		      else
		   {
			      //echo 'Error uploading file </br>';      
		   }
	    }	   
	      else
		   {
		          //echo 'Error: file '.$_FILES['image_upload']['name'].' Already Exists </br>';
		   }
    }
  	else
	{
		       //echo 'Error: files with formats jpg , jpeg , png , gif , bmp </br>';                       
    } 
	}
else
{
	     //echo "Thank you:No file uploaded<br>";
	}
	
	//-----------image2 uploading code Ends here---------------------------------
				
				
				
				
				
				
				
				$id = request_var('id','');
				$ip = $_SERVER['REMOTE_ADDR'];
				$parientid = request_var('parientid','');
				$username =  request_var('username','');
				//$email =  request_var('email','');
				$password = request_var('password','');
				$gender = request_var('gender','');
				$seeking_gender = request_var('seeking_gender','');
				$dateofbirth = request_var('dateofbirth','');
				$country = request_var('country','');
				$state = request_var('state','');
				$city = request_var('city','');
				$terms = request_var('terms','');
				$first_name = request_var('first_name','');
				$last_name = request_var('last_name','');
				$phone = request_var('phone','');
				$mobile = request_var('mobile','');
				//$profile_picture = request_var('profile_picture','');
				$profile_date = request_var('profile_date','');
				$profile_time = request_var('profile_time','');
				$haircolor = request_var('haircolor','');
				$hairtype = request_var('hairtype','');
				$eyecolor = request_var('eyecolor','');
				$eyewear = request_var('eyewear','');
				$height = request_var('height','');
				$weight = request_var('weight','');
				$bodytype = request_var('bodytype','');
				$appearance = request_var('appearance','');
				$facialhair = request_var('facialhair','');
				$physicalstatus = request_var('physicalstatus','');
				$maritalstatus = request_var('maritalstatus','');
				$children = request_var('children','');
				$familyvalues = request_var('familyvalues','');
				$livingsituation = request_var('livingsituation','');
				$likes = request_var('likes','');
				$dislikes = request_var('dislikes','');
				$zodiachormony = request_var('zodiachormony','');
				$foodhabits = request_var('foodhabits','');
				$education = request_var('education','');
				$occupation = request_var('occupation','');
				$relocate = request_var('relocate','');
				$religion = request_var('religion','');
				$bornreverted = request_var('bornreverted','');
				$religiousvalues = request_var('religiousvalues','');
				$religiousservices = request_var('religiousservices','');
				$mother_language = request_var('mother_language','');
				$ethnicity = request_var('ethnicity','');
				$cast = request_var('cast','');
				$nationality = request_var('nationality','');
				$placeofbirth = request_var('placeofbirth','');
				$languagesspoken = request_var('languagesspoken','');
				$ambition = request_var('ambition','');
				$hobbies = request_var('hobbies','');
				$dreams = request_var('dreams','');
				$getmarried = request_var('getmarried','');
				$morechildren = request_var('morechildren','');
				$dowry = request_var('dowry','');
				$cnic = request_var('cnic','');
				//$family_image = request_var('family_image','');
				
				echo $email= $_SESSION['email'];
				
				$q = "UPDATE pairness_family SET id = '$id', ip = '$ip', familyid = '$parientid', familyfullname = '$username', familyemailaddress = '$email', familypassword = '$password', familygender = '$gender', familyseekinggender = '$seeking_gender', familydateofbirth = '$dateofbirth', familycountry = '$country', familystate = '$state', familycity = '$city', familyterms = '$terms', familyfirstname = '$first_name', familylastname = '$last_name', familyphone = '$phone', familycell = '$mobile', familyprofileimage = '$profile_picture', familyprofiledate = '$profile_date', familyprofiletime = '$profile_time', familyhaircolor = '$haircolor', familyhairtype = '$hairtype', familyeyecolor = '$eyecolor', familyeyewear = '$eyewear', familyheight = '$height', familyweight = '$weight', familybodytype = '$bodytype', familyappearance = '$appearance', familyfacialhair = '$facialhair', familyphysicalstatus = '$physicalstatus', familymaritalstatus = '$maritalstatus', familyhavechildrens = '$children', familyvalues = '$familyvalues', familylivingsituation = '$livingsituation', familylikes = '$likes', familydislikes = '$dislikes', familyzodiachormony = '$zodiachormony', familyfoodhabits = '$foodhabits', familyeducation = '$education', familyoccupation = '$occupation', familyrelocate = '$relocate', familyreligion = '$religion', familybornreverted = '$bornreverted', familyreligiousvalues = '$religiousvalues', familyattendreligiousservices = '$religiousservices', familymothertongue = '$mother_language', familyethnicity = '$ethnicity', familycast = '$cast', familynationality = '$nationality', familyplaceofbirth = '$placeofbirth', familylanguagesspoken = '$languagesspoken', familyambition = '$ambition', familyhobbies = '$hobbies', familydreams = '$dreams', familygetmarried = '$getmarried', familywantmorechildrens = '$morechildren', familydowry = '$dowry', familycnicnumber = '$cnic', familycnicimage = '$family_image' WHERE familyemailaddress = '$email';";
				
				if($mysqli->query($q)){
				$arr = array('s' => 1,'e'=>'Profile successfully updated!');
				echo json_encode($arr);	
				}else{
				$arr = array('s' => 0,'e'=>'There was some error. Please try again.');
				echo json_encode($arr);
				}				
  
  }else{
  $arr = array('s' => 0,'e'=>'Only Post requests are accepted');
				echo json_encode($arr);
  }
	 
	?>	