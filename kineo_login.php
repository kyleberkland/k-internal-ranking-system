<?php
session_start();
require("connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
      
	    $myemail = mysqli_real_escape_string($con,$_POST['uemail']);
	    $mypassword = mysqli_real_escape_string($con,$_POST['upassword']); 
	      
	    $query = "SELECT uid,uemail,upassword,isadmin FROM tbl_users WHERE uemail = '$myemail' and upassword = '$mypassword'";
	    
	    $rsq = mysqli_query($con, $query);
		$rowcount=mysqli_num_rows($rsq);
		$rowq = $rsq->fetch_assoc();

		$_SESSION['isadmin'] = $rowq['isadmin'];

		if ($rowq['isadmin']==1) { 
			$_SESSION['login_user'] = $myemail;
        	$_SESSION['uid'] = $rowq['uid'];
		    header("location:admin.php");
		} else {
		   
      if($rowcount > 0) {
         $_SESSION['login_user'] = $myemail;
         $_SESSION['uid'] = $rowq['uid'];
         
         header("location: kineo_ranking.php");
      }else {
         echo "Your Login Name or Password is invalid";
      }

  }

      
  }
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/kineo_login.min.css">
<link rel="stylesheet" href="css/kineo_generic.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
	<title>Kineo</title>
</head>
<body>
<h1>Welcome to Kineo's Pay it forward</h1>

<div>
<form method = "post" action = " " >
   <label>Email: </label><input type = "text" name = "uemail" class = "box"/><br /><br />
   <label>Password: </label><input type = "password" name = "upassword" class = "box" /><br/><br />
   <input class="btn btn-login" type = "submit" value = " Login "/> 
</form>
</div>
<br>
<div class="btn-forgotpassword">
	<form action="forgot_password.php">
		<input class="btn" type = "submit" value = " Forgot password "/><br />	
	</form>
</div>
<br>
<p>If you haven't registered:</p>
<form action="http://192.168.123.30/personalwork/kineo_ranking/kineo_reg.php">
  <input type='submit' name='submit' value='Register' class='btn' />
</form>
</body>
</html>