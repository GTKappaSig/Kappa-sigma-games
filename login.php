<?php
include_once("./config.php");

if (isset($_COOKIE['email']) && isset($_COOKIE['password']) && $_COOKIE['email'] != NULL) {
    header('Location: user.php'); 
}

if (isset($_GET['e'])) {
	$e = cleanInput($_GET['e']);	
}

if (isset($_POST['create_user'])){
	$name = cleanInput($_POST['name']);
	$email = cleanInput($_POST['email']);
	$phone = cleanInput($_POST['phone']);
	$shirt = cleanInput($_POST['shirt']);
	$password1 = cleanInput($_POST['password1']);
	$password2 = cleanInput($_POST['password2']);
	
	if (empty($name)) {
		$e = "Name required!";
	} else if (empty($email)) {
		$e = "Email required!";
	} else if (empty($phone)) {
		$e = "Phone required!";
	} else if (!checkEmail($email)) {
		$e = "Invalid Email";
	} else if (isEmailUsed($email)) {
		$e = "Email already used!";
	} else if ($password1 != $password2) {
		$e = "Passwords do not match!";
	} else {
		createUser($name, $email, $phone, $shirt, $password1);
		$s = "User ".$email." created!";
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Login - Kick the Stigma</title>

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<!-- Navigation -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse"> <i class="fa fa-bars"></i> </button>
			<a class="navbar-brand" href="index.html#page-top"> <i class="fa fa-play-circle"></i> <span class="light" style="color: #ffcc61">Kick the</span> Stigma </a> </div>
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			<ul class="nav navbar-nav">
				<!-- Hidden li included to remove active class from about link when scrolled up past about section -->
				<li class="hidden"> <a href="#page-top"></a> </li>
				<li> <a href="index.html#about">About</a> </li>
				<li> <a  href="index.html#events">Events</a> </li>
				<li> <a  href="index.html#participate">Participate</a> </li>
				<li> <a  href="index.html#contact">Contact</a> </li>
			</ul>
		</div>
		<!-- /.navbar-collapse --> 
	</div>
	<!-- /.container --> 
</nav>

<!-- Intro Header -->
<header class="intro">
	<div class="intro-body">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<?php 
						if (isset($e)) echo '<div class="alert alert-danger" role="alert">'.$e.'</div>'; 
						if (isset($s)) echo '<div class="alert alert-success" role="alert">'.$s.'</div>';
					?>
					<div class="panel panel-default">
						<div class="panel-heading"> <strong>New User Registration</strong> </div>
						<div class="panel-body" style="color: #333">
							<form role="form" action="login.php" method="post" name="register">
								<br/>
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-user"  ></i></span>
									<input type="text" class="form-control" placeholder="Your Name" name="name"/>
								</div>
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
									<input type="text" class="form-control" placeholder="Your Email" name="email"/>
								</div>
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
									<input type="text" class="form-control" placeholder="Your Phone" name="phone" />
								</div>			
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
									<select class="form-control" name="shirt">
										<option value="s">Small Shirt</option>
										<option value="m">Medium Shirt</option>
										<option value="l">Large Shirt</option>
										<option value="xl">XL Shirt</option>
									</select>
								</div>							
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-lock"  ></i></span>
									<input type="password" class="form-control" placeholder="Enter Password" name="password1" />
								</div>
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-lock"  ></i></span>
									<input type="password" class="form-control" placeholder="Retype Password" name="password2" />
								</div>								
								<button class="btn btn-success " type="submit" id="create_user" name="create_user" >Register Me</button>
							</form>
							<hr />
							<h5 style="margin-bottom: 10px">Already Registered?</h5>
							<form role="form" action="user.php" method="post" name="login">
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-comment"></i></span>
									<input type="text" class="form-control" placeholder="Your Email" name="email"/>
								</div>
								<div class="form-group input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-lock"  ></i></span>
									<input type="password" class="form-control" placeholder="Enter Password" name="password"/>
								</div> 
								<button class="btn btn-primary " type="submit" id="login_user">Login</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- Footer -->
<footer>
	<div class="container text-center">
		<p>Adaptation and integration by <a href="http://www.philipbale.com">Philip Bale</a></p>
	</div>
</footer>

<!-- jQuery Version 1.11.0 --> 
<script src="js/jquery-1.11.0.js"></script> 

<!-- Bootstrap Core JavaScript --> 
<script src="js/bootstrap.min.js"></script> 
<!-- Custom Theme JavaScript --> 
<script src="js/custom.js"></script>
</body>
</html>
