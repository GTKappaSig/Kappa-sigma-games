<?php 
include_once("./config.php");

if (isset($_GET['e'])) {
	$e = cleanInput($_GET['e']);	
}

if (isset($_GET['logout'])) {
	logout('./login.php');	
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    if (checkUser($_POST['email'], $_POST['password'])) {  
        
		/* Cookie expires when browser closes */
		$_COOKIE['email'] = $_POST['email'];
		setcookie('email', $_POST['email'], false, '/');
		setcookie('password', $_POST['password'], false, '/');

    } else {
        header('Location: login.php?e=Login%20Failed');
    }
    
} else if (isset($_COOKIE['email']) && isset($_COOKIE['password']) && $_COOKIE['email'] != NULL) {
    // proceed as normal
} else {
    header('Location: login.php?e=Please%20Login!'); 
}

$user = getUserByEmail($_COOKIE['email']);

if (isset($_POST['create_team'])){
	$name = cleanInput($_POST['team_name']);
	if (isTeamUsed($name)) {
		$e = "Team name is already used!";	
	} else {
		$teamId = createTeam($name, $user['id']);
		$s = "Team ".$name." was successfully created!  Access code: ".($teamId * 7);
		joinTeam($user['id'], $teamId);
		$user['team_id'] = $teamId;
	}
	
} else if (isset($_POST['join_team'])){
	$teamId = cleanInput($_POST['team_id']) / 7;
	if (!isTeamValid($teamId)) {
		$e = "Access code is not valid";	
	} else if (getTeamCount($teamId) == 10) {
		$e = "Team is full!";
	} else {
		joinTeam($user['id'], $teamId);
		$s = "Succesfully joined team ".($teamId * 7);
		$user['team_id'] = $teamId;
	}
}

$team = getTeam($user['team_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Home - Kick the Stigma</title>

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
				<li class="hidden"> <a href="index.html#page-top"></a> </li>
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
			<div class="col-md-8 col-md-offset-2">
				<?php 
					if (isset($e)) echo '<div class="alert alert-danger" role="alert">'.$e.'</div>'; 
					if (isset($s)) echo '<div class="alert alert-success" role="alert">'.$s.'</div>';
				?>
				<div class="panel panel-default" style= "color: #333;">
					<div class="panel-heading"> <strong>User Info</strong> <a href="user.php?logout=t" style="float: right; margin-top: -4px;" class="btn btn-danger btn-sm">Logout</a></div>
					<div class="panel-body" style=" text-align: left">
						<div class "row"> <?php echo'
							<div class="col-md-4" style="margin-bottom: 5px"> <strong>Name:</strong> '.$user['name'].'<br/>
								<strong>Phone: </strong>'.$user['phone'].'<br />
								<strong>Email: </strong>'.$user['email'].'<br />
								<strong>Team: </strong>'.$team['name'].'<br />
								<strong>Team Code: </strong>'.($team['id'] * 7).' ('.getTeamCount($team['id']).' registered)<br />
								<strong>Shirt Size: </strong>'.$team['shirt'].'<br />
								<strong>Payment: </strong>'.($user['paid'] ? "Received" : "Not yet received").'</div>';
							?>
							<div class="col-md-8" style="">
								<?php if ($user['team_id'] != 1) {?>
								<strong>Team Members</strong>
								<table class="table table-hover table-condensed">
									<tr>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Paid?</th>
									</tr>
									<?php 
									$result = getTeamMemberArray($user['team_id']);
									while($row = mysql_fetch_array($result)) {
										echo '<tr><td>'.$row['name'].'</td>';
										echo '<td>'.$row['email'].'</td>';
										echo '<td>'.$row['phone'].'</td>';
										echo '<td>'.($row['paid'] ? "Yes" : "No").'</td></tr>';
									}
									?>
								</table>
								<?php } else { ?>
								<h5 style="margin-bottom: 8px">Join Team</h5>
								<form class="form-horizontal" role="form" action="user.php" method="post">
									<div class="form-group">
										<div class="col-md-6" >
											<input type="text" class="form-control" id="inputType" placeholder="Access Code" name="team_id">
										</div>
										<div class="col-md-6">
											<button style="width: 100%" class="btn btn-primary " type="submit" name="join_team">Join</button>
										</div>
									</div>
								</form>
								<h5 style="margin-bottom: 8px">Create Team</h5>
								<form class="form-horizontal" role="form" action="user.php" method="post">
									<div class="form-group">
										<div class="col-md-6" >
											<input type="text" class="form-control" id="inputType" placeholder="Name" name="team_name">
										</div>
										<div class="col-md-6">
											<button style="width: 100%" class="btn btn-primary " type="submit" name="create_team">Create</button>
										</div>
									</div>
								</form>
								<?php } ?>
							</div>
						</div>
						<?php if (!$user['paid']) { ?>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="HAZ5FL2LVV4CW">
						<input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
						<input type="hidden" name="on0" value="<?php echo $user['id']; ?>">
						<button class="btn btn-success " type="submit" style="width: 100%; margin-top: 5px;">Pay Registration Fee</button>
						</form>

						<?php } else { ?>
							<p>You must sign and print <a href="https://docs.google.com/forms/d/1dfCGMcJ5EsFvkdOPYe7WUsWnlTXcCJpRJrv7szdkrPg/viewform">this waiver</a>.</p>
						<?php } ?>
						
					</div>
					<h6 style="margin-bottom: 10px;">If you have any issues or need any info changed, please contact us!</h6>
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
