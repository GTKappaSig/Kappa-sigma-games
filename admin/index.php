<?php 
include_once("../config.php");
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
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- Custom CSS -->
<link href="../css/style.css" rel="stylesheet">

<!-- Custom Fonts -->
<link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<!-- Navigation -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse"> <i class="fa fa-bars"></i> </button>
			<a class="navbar-brand" href="../index.html#page-top"> <i class="fa fa-play-circle"></i> <span class="light" style="color: #ffcc61">Kick the</span> Stigma </a> </div>
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse navbar-right navbar-main-collapse">
			<ul class="nav navbar-nav">
				<!-- Hidden li included to remove active class from about link when scrolled up past about section -->
				<li class="hidden"> <a href="index.html#page-top"></a> </li>
				<li> <a href="../index.html#about">About</a> </li>
				<li> <a  href="../index.html#events">Events</a> </li>
				<li> <a  href="../index.html#participate">Participate</a> </li>
				<li> <a  href="../index.html#contact">Contact</a> </li>
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
		<div class="col-md-8 col-md-offset-2"> <br />
			<br />
			<br />
			<br />
			<div class="panel panel-default" style= "color: #333;">
				<div class="panel-heading"> <strong>User Info</strong> <a href="../index.php" style="float: right; margin-top: -4px;" class="btn btn-danger btn-sm">Logout</a></div>
				<div class="panel-body" style=" text-align: left">
					<div class "row">
						<div class="col-md-2" style="margin-bottom: 5px"> <strong>Name:</strong> Admin<br/>
							<?php echo'
								<strong>Users: </strong>'.getUserCount().'<br /> 
								<strong>Paid: </strong>'.getPaidCount().'<br />
								<strong>Teams: </strong>'.getTotalTeamCount().'<br />'.'</div>';
							?>
							<div class="col-md-10" style=""> <strong>Philip better get a free t-shirt</strong>
								<table class="table table-hover table-condensed">
									<tr>
										<th>Name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Team</th>
										<th>Size</th>
										<th>Paid?</th>
									</tr>
									<?php 
									$result = getAllUsers();
									while($row = mysql_fetch_array($result)) {
										echo '<tr><td>'.$row['userName'].'</td>';
										echo '<td>'.$row['email'].'</td>';
										echo '<td>'.$row['phone'].'</td>';
										echo '<td>'.$row['name'].'</td>';
										echo '<td>'.$row['shirt'].'</td>';
										echo '<td>'.($row['paid'] ? "Yes" : "No").'</td></tr>';
									}
									?>
								</table>
								<br />
								<br />
								<table class="table table-hover table-condensed">
									<tr>
										<th>Id</th>
										<th>Name</th>
										<th>Members</th>
										<th>Founder</th>
										<th># Paid</th>
									</tr>
									<?php 
									$result = getAllTeams();
									while($row = mysql_fetch_array($result)) {
										$admin = getUser($row['admin_id']);
										echo '<tr><td>'.($row['id'] * 7).'</td>';
										echo '<td>'.$row['name'].'</td>';
										echo '<td>'.getTeamCount($row['id']).'</td>';
										echo '<td>'.$admin['name'].'</td>';
										echo '<td>'.getTeamCountPaid($row['id']).'</td></tr>';
									}
									?>
								</table>
							</div>
						</div>
					</div>
					<h6 style="margin-bottom: 10px;">Philip Bale is the shit. Faz is ugly.</h6>
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
<script src="../js/jquery-1.11.0.js"></script> 

<!-- Bootstrap Core JavaScript --> 
<script src="../js/bootstrap.min.js"></script> 
<!-- Custom Theme JavaScript --> 
<script src="../js/custom.js"></script>
</body>
</html>
