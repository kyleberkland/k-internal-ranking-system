<?php 
session_start();
require("connection.php");

 //Retrieving points from current user. 
  $query = mysqli_query($con, "SELECT * FROM tbl_users WHERE uemail='".$_SESSION['login_user']."'");
  $row = $query->fetch_assoc();

  	//Find diff
  	if($row['upoints'] >= $_REQUEST['rating']){

	//update comment
	$comment = $_REQUEST['comment'];
	if (empty($_REQUEST["comment"])) {
		$comment = "";
		$_SESSION["errMsg"] = "Error: No comment submitted in request";
	} else {
		$updateComment = "INSERT INTO tbl_rating(cuid,cuidto,ccomment,postedon,tvid,trate) VALUES(".$_SESSION['uid'].",".$_REQUEST['uname'].",'".$_REQUEST['comment']."',NOW(),".$_REQUEST['kineoVal'].",".$_REQUEST['rating'].")";
		
		if ( mysqli_query($con, $updateComment) ){

			  	//deduct points from current user.
				$updateRating = "UPDATE tbl_users SET upoints = upoints - ".$_REQUEST['rating']." WHERE uemail = '".$_SESSION['login_user']."'";
				
				mysqli_query($con, $updateRating);	
				$_SESSION["errMsg"] = $_REQUEST['rating']. " point(s) assigned";

				$_SESSION["errMsg"] = $_REQUEST['rating']." point(s) submitted";

		}else{
			$_SESSION["errMsg"] = "Error: Unable to submit points";
		}
	}



  	} else {
  		$_SESSION["errMsg"] = "Not enough points to assign";
  	}
  	header("Location: kineo_ranking.php");
 ?>