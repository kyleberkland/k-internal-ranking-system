<?php
   session_start();
   
   if(session_destroy()) {
      header("Location: kineo_login.php");
   }
?>