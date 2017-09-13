

<!DOCTYPE html>
<html>
<head>
	<title>Forgot password</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-grid.min.css">
	<link rel="stylesheet" href="css/bootstrap-reboot.min.css">
	<link rel="stylesheet" href="css/kineo_generic.min.css">
</head>
<body>
<form action="https://formspree.io/kyle.berkland@kineo.co.nz"
      method="POST">
    <input type="text" name="name" value="Enter name...">
    <input type="email" name="_replyto" value="Enter your email...">
    <input type="hidden" name="_next" value="http://192.168.123.30/personalwork/kineo_ranking/thankyou.php" />
    <input type="submit" value="Forgot password">
</form>
<br>
<div>
	<form action="http://192.168.123.30/personalwork/kineo_ranking/kineo_login.php">
	  <input type='submit' name='submit' value='Return to Login' class='btn return-lto-login' />
	</form>
</div>
</body>
</html>
