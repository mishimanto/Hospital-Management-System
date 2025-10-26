<?php 
require_once("include/config.php");
if(!empty($_POST["email"])) {
	$email= $_POST["email"];	
	$result =mysqli_query($con,"SELECT email FROM users WHERE email='$email'");
	$count=mysqli_num_rows($result);
	if($count>0)
	{
		echo "Email already exists";
	} 
	else
	{		
		echo "Email available for Registration";	 
	}
}


?>
