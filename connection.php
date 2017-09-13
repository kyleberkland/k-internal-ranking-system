<?php
 $dbname = "kineo_ranking_db";
 $dbuser = "root";
 $dbpass = "";
 $dbhost = "localhost";

 $con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

 // Check connection
 if (mysqli_connect_errno())
 {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

?>