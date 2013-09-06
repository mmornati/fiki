<?php
include("authenticate.php");
session_start();
// check to see if user is logging out
if(isset($_GET['out'])) {
        // destroy session
        session_unset();
        $_SESSION = array();
        unset($_SESSION['user'],$_SESSION['access']);
        session_destroy();
}

// check to see if login form has been submitted
if(isset($_POST['username'])){
        // run information through authenticator
        if(authenticate($_POST['username'],$_POST['password']))
        {
                // authentication passed
                header("Location: index.php");
                die();
        } else {
                // authentication failed
                $error = 1;
        }
}

// output error to user
//if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";

// output logout success
//if (isset($_GET['out'])) echo "Logout successful<br />";
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>Fiki - The file based wiki</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
<div class="container">
	<section id="content">
		<form method="post" action="login.php">
			<h1>Login Form</h1>
			<div>
				<input type="text" placeholder="Username" required="" name="username" id="username" />
			</div>
			<div>
				<input type="password" placeholder="Password" required="" name="password" id="password" />
			</div>
			<div>
			<?php if (isset($error)) echo "Login failed: Incorrect user name, password, or rights<br />";?>
			</div>
			<div>
				<input type="submit" value="Log in" />
				<!-- <a href="#">Lost your password?</a> -->
				<!-- <a href="#">Register</a> -->
			</div>
		</form><!-- form -->
	</section><!-- content -->
</div><!-- container -->
</body>
</html>
