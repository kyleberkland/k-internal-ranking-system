<?php
require("connection.php");
if( isset($_REQUEST["uname"]) ) {
	echo ">>";
	if( $_REQUEST["uname"] !="" && $_REQUEST["uemail"] !="" && $_REQUEST["upassword"] !="") {
		
		if($con) {
			$sql_user  = "select count(*) as totalRecords from tbl_users where uemail = '".$_REQUEST['uemail']."'";
			$rs_user = mysqli_query($con, $sql_user);
			$row_user = mysqli_fetch_object($rs_user);
			if ( $row_user->totalRecords > 0) {
				echo "User already exist";
			} else {
				//Create user
				$sql_user  = "insert into tbl_users set 
							  uemail = '".$_REQUEST['uemail']."', 
							  uname =  '".$_REQUEST['uname']."', 
							  upassword =  '".$_REQUEST['upassword']."'
							  ";
				echo $sql_user;
				if (mysqli_query($con, $sql_user)) {
					echo "User created!";
					header("location: kineo_login.php");
				} else {
					echo "Some error occured!";
				}
				
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/kineo_login.min.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/kineo_generic.min.css">
	<title></title>
</head>
<body class="container">
<h1>Register here</h1>

<form method="post" action="kineo_reg.php">
	Name: <input type="text" name="uname"><br>
	Email: <input type="text" name="uemail"><br>
	Password: <input type="password" name="upassword"><br>
	<input class="btn" type="submit" name="submit" value="Submit">

</form>

To log in page, <a href="kineo_login.php">click
here</a>.

</body>
</html>