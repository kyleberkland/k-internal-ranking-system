
<?php
session_start();
require("connection.php");

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


<?php
if(isset($_POST['btn-upload']))
{    
   $currentUser = $_SESSION['uid'];    
   
   $tmpFile = $_FILES['file']['tmp_name'];
   $folder="img/avatar/";


   $info = getimagesize($tmpFile);
   $extension = image_type_to_extension($info[2]);

   $file = rand(1000,100000)."-".md5($_FILES['file']['name'])."-".time().$extension;
  
   move_uploaded_file($tmpFile, $folder.$file);
    
   $sql="update `tbl_users` set avatar = '".$file."' where uid='".$currentUser."'";
   //echo $sql;
   if(!$insert=mysqli_query($con,$sql)) {
      $imgUploadError = true;
   } else {
     $removeFile = "img/avatar/".$_POST['oldname'];
     //echo $removeFile;
     unlink($removeFile);
   }

  
}

?>

<html>
<head>
<!-- loading bootstrap styles -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="css/bootstrap-reboot.min.css">
<!-- loading my styles -->
  <link rel="stylesheet" href="css/kineo_landing.min.css">
  <link rel="stylesheet" href="css/kineo_generic.min.css">
  <link rel="stylesheet" type="text/css" href="plugin/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="plugin/slick/slick-theme.css"/>
<!-- loading jquery -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <style type="text/css">
      .hideform {
        display: none;
      }

  </style>
  <script type="text/javascript">
      function editImage() {
          $('#editImage').removeClass("hideform");
      }

  </script>

</head>
<body class="container">
  <div class="wrapper">
    <div style="padding:20px;" class="header-wrap">
    <h1 class="nav-title">PAY IT FORWARD</h1>
    <h6 class="nav-sub-title">Kineo internal reward and recognition system</h6>
      <div class="nav">
      <div class="nav-btns">
      <div class="btn-logout-wrap">
        <h2><a class="btn btn-logout" href = "logout.php">Sign Out</a></h2>
        </div>
          <?php
          $currentUser = $_SESSION['login_user'];
          if ($currentUser=='admin') { 
               
                  $link_address = "admin.php";
                  echo "<a  class='btn' href='".$link_address."'>Admin page</a>";
              }else{

              }
          ?> 
       </div>
    </div>
  </div>

<?php 
  $currentUser = $_SESSION['login_user'];
  $query = mysqli_query($con, "SELECT * FROM tbl_users WHERE uemail='$currentUser'");
 //Retrieving points from current user. 

  $row = $query->fetch_assoc();

  //Recieve users points
  $queryp = mysqli_query($con, "select sum(trate) as tpoints from tbl_rating where cuidto = ".$_SESSION['uid']);
  $rowp = $queryp->fetch_assoc();  
    //Display user points
    echo "<h1>Welcome ". $row['uname'] ." (". ($rowp['tpoints'] ? $rowp['tpoints']: 0).")</h1>";
    $amountOfPoints = $row['upoints'];

  //If user's points is equal to or less than 0.
  if($amountOfPoints <= 0){ 
    echo "You have no more points for his month! Come back next month!";  
  }else{
    echo "<div><p style='color:green;'>You have " . $amountOfPoints . " points to spend.</p></div>";  
?>








<?php
if ($row['avatar'] == "") 
{ 
?>
<form action="kineo_ranking.php" method="post" enctype="multipart/form-data">
<input type="file" name="file" />
<button type="submit" name="btn-upload">upload</button>
</form>
<div class="mask-avatar">
  <img src="img/avatar/default.jpg" />
</div>
<?php 
} else {
  echo "<a href=\"javascript:void(0);\" onclick = \"editImage()\">Edit</a>";
  ?>
  <form action="kineo_ranking.php" method="post" enctype="multipart/form-data" id='editImage' class="hideform">
<input type="file" name="file" />
<input type="hidden" name="oldname" value="<?php echo $row['avatar']; ?>" />
<button type="submit" name="btn-upload">upload</button>
</form>
<?php
  echo '<div class="wrap-avatar-table">
  <div class="mask-avatar"><img src="img/avatar/'.$row['avatar'].'"/></div>
  <table border=1 cellspacing=0 cellpadding=1 width=100%><th>My points won</th><th>Points assigned</th><th>Group Points</th>
  <tr>
  <td>ok</td>
  <td>ok</td>
  <td>ok</td>
  </tr>
  </table>
  </div>';
}
?>
<div class="container-fluid kineo-value-box-wrapper">
  <div class="kineo-value-box">
    <?php 

//Points for each Kineo value
 $queryV= "SELECT v.tid, v.tval, SUM(r.trate) AS totrating FROM tbl_values v LEFT JOIN tbl_rating r ON v.tid=r.tvid GROUP BY v.tid order by totrating DESC";

        
        $query2 = mysqli_query($con, $queryV);
        $rowcountV=mysqli_num_rows($query2);

        $queryTotalValue = "SELECT SUM(trate) AS totalrating FROM tbl_rating";
        $query3 = mysqli_query($con, $queryTotalValue);
        $rowcountX=mysqli_num_rows($query3);
         if ($rowcountV<=0) {
          echo "<p>0</p>";
        } else {
          echo "<table border=1 cellspacing=0 cellpadding=1 width=100%><th>Kineo Value</th><th>Group Points</th>";
          while($rowV = $query2->fetch_assoc()){
            echo "<tr>";
            echo "<td width=10%;>".$rowV['tval']."</td>";
            $totalPoints = $rowV['totrating'] ? $rowV['totrating'] : 0;
            echo "<td width=10%;>".$totalPoints."</td>";

            echo "</tr>";
          }
        }
         if ($rowcountX<=0) {
          echo "<p>0</p>";
          }else{
          while($rowX = $query3->fetch_assoc()){
              echo "<tr>";
              echo "<td>Total Team Value points</td>";
              $totalVPoints = $rowX['totalrating'] ? $rowX['totalrating'] : 0;
              echo "<td width=10%;>".$totalVPoints."</td>";
              echo "</tr>";
            }
          echo "</table>";
        }
    ?>
  </div>

</div>

<div class="container-fluid">
 <form action="kineo_ranking_process.php" method="post">
 <?php
 $allusers = "SELECT * FROM tbl_users where uemail != '".$currentUser."'";


  if (isset($_SESSION["errMsg"]) && $_SESSION["errMsg"]!="") {
    echo "<p style='color:red;'>".$_SESSION["errMsg"]."</p>";
    $_SESSION["errMsg"] = "";
  }

 ?>
 <p>Select a Team member to assign points:</p>
<select name="uname">
<?php 
//Retrieving user's from db.

  $query = mysqli_query($con, $allusers);
  while ($row = $query->fetch_assoc()){
  echo "<option value=\"". $row['uid']."\">" . $row['uname'] . "</option>";
  }

   if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedUser = isset($_POST['query'])? $_POST['query']: '';
  }

?>
</select>
<p>Select a rating:</p>
<div class="stars" style="display:block;">
    <input class="star star-5" name="rating" id="rating5" value="5" type="radio" name="upoints"/>
    <label class="star star-5" for="rating5"></label>
    <input class="star star-4" name="rating" id="rating4" value="4" type="radio" name="upoints"/>
    <label class="star star-4" for="rating4"></label>
    <input class="star star-3" name="rating" id="rating3" value="3" type="radio" name="upoints"/>
    <label class="star star-3" for="rating3"></label>
    <input class="star star-2" name="rating" id="rating2" value="2" type="radio" name="upoints"/>
    <label class="star star-2" for="rating2"></label>
    <input class="star star-1" name="rating" id="rating1" value="1" type="radio" name="upoints"/>
    <label class="star star-1" for="rating1"></label>

    <?php 
      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['rating'])? $_POST['rating']: '';
      }

    ?>
    
  </div>
  
  <div class="kineo-value-wrapper">
  <?php 
  
?>
<p>Pick a Kineo Value:</p>
    <div class="pick-value">
      <select name="kineoVal" class="kineoVal">
      <?php
      $getvaluetbl = "SELECT * FROM tbl_values";
        $getValue = mysqli_query($con, $getvaluetbl);
          while ($getRow = $getValue->fetch_assoc()){
          echo "<option value=\"". $getRow['tid']."\">" . $getRow['tval'] . "</option>";
          }
      ?>
      </select>
    </div>
  </div>
  <?php
        
  ?>

  <br>
    <p>Add a comment:</p> <textarea name="comment" rows="5" cols="40"></textarea>
  <br>
    <br>
    <button class="btn btn-submit-rating btn-submit" id="btnsubmitrating" type="submit" value="Submit" disabled="disabled" name="submit">Submit</button>  
</form>
</div>



<div class="slide-baby-slide" style="border:2px solid #000;">
   <div>content1</div>
    <div>content2</div>
    <div>content3</div>
</div>


<div class="container-fluid">
  <div class="comment-section">
      <h3>Comment section:</h3>
      <div class="display-comments">
        <?php
        $queryj= "SELECT SendingUser.uname Sender, ReceivingUser.uname Receiver, tbl_rating.ccomment , tbl_rating.postedon
                  FROM tbl_rating 
                  JOIN tbl_users SendingUser
                  ON tbl_rating.cuid = SendingUser.uid 
                  JOIN tbl_users ReceivingUser 
                  ON tbl_rating.cuidto = ReceivingUser.uid
                  order by  cid DESC";
        

        $query1 = mysqli_query($con, $queryj);
        $rowcount=mysqli_num_rows($query1);
        if ($rowcount<=0) {
          echo "<p>No comments</p>";
        } else {
          echo "<table border=1 cellspacing=0 cellpadding=1 width=100%><th>Sender</th><th>Receiver</th><th>Comment</th><th>Posted On</th>";
          while($row1 = $query1->fetch_assoc()){
            echo "<tr>";
            echo "<td width=10%;>".$row1['Sender']."</td>";
            echo "<td width=10%;>".$row1['Receiver']."</td>";
            echo "<td width=70%;>".$row1['ccomment']."</td>";
            echo "<td width=10%>".$row1['postedon']."</td>";
            /*echo "<pre>";
            print_r($row1);
            echo "</pre>";*/
            echo "</tr>";
          }
          echo "</table>";
        }
        ?>
      </div>
  </div>
</div>


<?php
}
?>

<script>
  $('label').click(function() {  
    if ($('#btnsubmitrating').is(':disabled')) {  
        $('#btnsubmitrating').removeAttr('disabled');  
    } else {  
        //$('#btnsubmitrating').attr('disabled', 'disabled');  
    }  
});  

// $("select.kineoVal option").unwrap().each(function() {
//     var btn = $('<div class="btn">'+$(this).text()+'</div>');
//     if($(this).is(':checked')) btn.addClass('on');
//     $(this).replaceWith(btn);
// });

// $(document).on('click', '.btn', function() {
//     $('.btn').removeClass('on');
//     $(this).addClass('on');
// });


</script>
<style>
  
 /* div.btn {
    display: inline-block;
    border: 2px solid #ccc;
    margin-right: 5px;
    padding: 2px 5px;
    cursor: pointer;
}
div.btn.on {
    background-color: #777;
    color: white;
}*/
</style>
<script type="text/javascript" href="js/bootstrap.min.js"></script> 
<script type="text/javascript" href="js/custom.js"></script> 
 <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>

<script type="text/javascript" src="plugin/slick/slick.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
  $('.slide-baby-slide').slick({
    dots: true,
    infinite: true,
    speed: 700,
    autoplay:false,
    autoplaySpeed: 2000,
    arrows:true,
    slidesToShow: 1,
    slidesToScroll: 1,
    easing: 'linear',
    draggable: false,
    dotsClass: 'slick-dots',
    useCSS: true,
    useTransform: true
  });
});
</script>
    </div>
  </body>
</html>



