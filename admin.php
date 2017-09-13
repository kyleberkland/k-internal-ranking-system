<?php
session_start();

require("connection.php");

require("node_modules/fusioncharts/fusioncharts.php");


//print_r($_REQUEST);

if (empty($_SESSION['count'])) {
   $_SESSION['count'] = 1;
} else {
   $_SESSION['count']++;
}


//Check if user is logged in
if (empty($_SESSION['uid'])) {
   header("Location: kineo_login.php");
} else {

}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  
	  <script src="node_modules/fusioncharts/fusioncharts.js"></script>
	  <script src="node_modules/fusioncharts/fusioncharts.charts.js"></script>
	  <script src="node_modules/fusioncharts/themes/fusioncharts.theme.zune.js"></script>


	<title>Admin page</title>

  <style>
    .container-fluid{
      width:50%;
      float:left;
    }
    .header-wrap{
      padding: 20px;
      position: relative;
      display: block;
      height: 100px;
      background: #f2f2f2;
      margin-bottom: 20px;
    }
    .nav{
      float: right;
    }
    .nav-title{
      float: left;
    }
  </style>
</head>
<body>
<div class="wrapper" style="padding:0px 20px;">
  <div style="padding:20px;" class="header-wrap">
    <h1 class="nav-title">ADMIN PAGE</h1>
      <div class="nav">
        <h2><a class="btn btn-main" href = "kineo_ranking.php">To dashboard</a></h2>
        <h2><a class="btn btn-logout" href = "logout.php">Sign Out</a></h2>
    </div>
  </div>
<div class="container">

<?php 
//Joint table and get users points 
  $queryjoin = mysqli_query($con, "SELECT u.uid, u.upoints as userSpendingPoints, u.uname as ListOfUsers, sum(r.trate) as usersTotal FROM tbl_users u LEFT JOIN tbl_rating r ON u.uid=r.cuidto GROUP BY u.uid order by usersTotal DESC");
  
echo "<table border=1 cellspacing=0 cellpadding=1 width=100%><th>Users</th><th>User points won</th><th>User points left to spend</th>";
  while($rowQ = mysqli_fetch_array($queryjoin)){
            echo "<tr>";
            echo "<td width=40%;>".$rowQ['ListOfUsers']."</td>";
            $userTotal = $rowQ['usersTotal'] ? $rowQ['usersTotal'] : 0;
            echo "<td width=30%;>".$userTotal."</td>";
            $userspendingdone = $rowQ['userSpendingPoints'] ? $rowQ['userSpendingPoints'] : 0;
            echo "<td width=30%;>".$userspendingdone."</td>";
            echo "</tr>";  
          }
 echo "</table>";
 ?>


</div>
<!--
	 ######  ##     ##    ###    ########  ########       ##
	##    ## ##     ##   ## ##   ##     ##    ##        ####
	##       ##     ##  ##   ##  ##     ##    ##          ##
	##       ######### ##     ## ########     ##          ##
	##       ##     ## ######### ##   ##      ##          ##
	##    ## ##     ## ##     ## ##    ##     ##          ##
	 ######  ##     ## ##     ## ##     ##    ##        ######
 -->
<!--
	##     ##    ###    ##       ##     ## ########  ######
	##     ##   ## ##   ##       ##     ## ##       ##    ##
	##     ##  ##   ##  ##       ##     ## ##       ##
	##     ## ##     ## ##       ##     ## ######    ######
	 ##   ##  ######### ##       ##     ## ##             ##
	  ## ##   ##     ## ##       ##     ## ##       ##    ##
	   ###    ##     ## ########  #######  ########  ######
 -->

<?php

     	// Form the SQL query that returns the top 10 most populous countries
     	$strQuery = "SELECT v.tid, v.tval, SUM(r.trate) AS totrating FROM tbl_values v LEFT JOIN tbl_rating r ON v.tid=r.tvid GROUP BY v.tid order by totrating DESC";

     	// Execute the query, or else return the error message.
     	$result = $con->query($strQuery) or exit("Error code ({$con->errno}): {$con->error}");

     	//print_r($result);


     	// If the query returns a valid response, prepare the JSON string
     	if ($result) {
        	// The `$arrData` array holds the chart attributes and data
        	$arrData = array(
        	    "chart" => array(
                  "caption" => "Top Kineo Values Points in each category",
                  "showValues" => "0",
                  "theme" => "zune"
              	)
           	);

        	$arrData["data"] = array();

	// Push the data into the array
        	while($row = mysqli_fetch_array($result)) {
           	array_push($arrData["data"], array(
              	"label" => $row["tval"],
              	"value" => $row["totrating"]
              	)
           	);
        	}
        	//print_r($arrData);

        	/*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        	$jsonEncodedData = json_encode($arrData);

	/*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

        	$columnChart = new FusionCharts("column2D", "myFirstChart" , 600, 300, "top-kineo-values", "json", $jsonEncodedData);

        	// Render the chart
        	$columnChart->render();

     	}

?>

  	

<div class="container-fluid">
  	<div id="top-kineo-values"><!-- Fusion Charts will render here--></div>
</div>



<?php

//chart 2
           	// Form the SQL query that returns the top 10 most populous countries
     	$strQuery2 = "SELECT v.tid, v.tval, SUM(r.trate) AS totrating2 FROM tbl_values v LEFT JOIN tbl_rating r ON v.tid=r.tvid GROUP BY v.tid order by totrating2 DESC";

     	// Execute the query, or else return the error message.
     	$result2 = $con->query($strQuery2);

     	//print_r($result2);


     	// If the query returns a valid response, prepare the JSON string
     	if ($result2) {
        	// The `$arrData` array holds the chart attributes and data
        	$arrData2 = array(
        	    "chart" => array(
                  "caption" => "Top Kineo Values - Pie chart percentage",
                  "showValues" => "0",
                  "theme" => "zune"
              	)
           	);

        	$arrData2["data"] = array();

	// Push the data into the array
        	while($row = mysqli_fetch_array($result2)) {
           	array_push($arrData2["data"], array(
              	"label" => $row["tval"],
              	"value" => $row["totrating2"]
              	)
           	);
        	}
        	//print_r($arrData2);

        	/*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

        	$jsonEncodedData2 = json_encode($arrData2);

	/*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

        	$columnChart2 = new FusionCharts("pie2d", "id2" , 600, 600, "who-chart", "json", $jsonEncodedData2);

        	// Render the chart
        	$columnChart2->render();

        	// Close the database connection
        	$con->close();
     	}

?>


<div class="container-fluid">
  	<div id="who-chart"><!-- Fusion Charts will render here--></div>
</div>
</div>
</body>
</html>