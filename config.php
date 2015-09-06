<?php 
require_once "Mail.php";
$db_host = "localhost";
$db_admin = "logik_ksgames";
$db_admin_pass = "ksgamespass"; 
$db_main = "logik_ksgames";
connect($db_admin, $db_admin_pass, $db_main);

date_default_timezone_set("America/New_York"); 

function connect($db_user, $db_pass, $db_database) { 
 mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
 mysql_select_db($db_database) or die(mysql_error());
}

function doesRecordExist($table, $field, $value) {
	$result = mysql_query("SELECT * FROM `$table` WHERE `$field` = '$value'") or die(mysql_error()); 
	$num_rows = mysql_num_rows($result);
 	if ($num_rows > 0)
 		return 1;
	else
		return 0; 
}

function getUserByEmail($email) { 
$result = mysql_query("SELECT * FROM `players_2015` WHERE `email` = '$email'") or die(mysql_error()); 
$row = mysql_fetch_array($result);  	
return $row;
}

function getPasswordByEmail($email) { 
$result = mysql_query("SELECT * FROM `players_2015` WHERE `email` = '$email'") or die(mysql_error()); 
$row = mysql_fetch_array($result);  	
return $row['password'];
}

function getUser($id) {
$result = mysql_query("SELECT * FROM `players_2015` WHERE `id` = '$id'") or die(mysql_error()); 
$row = mysql_fetch_array($result);  	
return $row;
}

function isLoggedIn() {
	if ((isset($_COOKIE['email']) && isset($_COOKIE['password'])) && (checkUser($_COOKIE['email'], $_COOKIE['password']) > 0))
		return 1;
	else 
		return 0;
}

function checkUser($email, $pass) {  
	$password = getPasswordByEmail($email);  
    if (!empty($password) && $password == $pass)   
        return 1;
     else
	return 0;
}

function isEmailUsed($email) {
 $result = mysql_query("SELECT * FROM `players_2015` WHERE `email` = '$email'") or die(mysql_error()); 
 $num_rows = mysql_num_rows($result);
 if ($num_rows > 0) {
 	return 1;
 } else {
	return 0;
 }
}

function markPaid($id, $txn_id, $payer_email) {
	mysql_query("UPDATE `players_2015` SET `trans_id` = '$txn_id', `paid` = 1, `payer_email` = '$payer_email' WHERE `id` = '$id';") or die(mysql_error());
}

function createUser($name, $email, $phone, $shirt, $pass) {
	mysql_query("INSERT INTO `players_2015` (`name`, `email`, `phone`, `shirt`, `password`) values ('$name', '$email', '$phone', '$shirt', '$pass')") or die(mysql_error());
	emailUser($email, $name);
}

function emailUser($to, $name) {
	$from = 'KappaSigmaGames@gmail.com';
	$subject = 'Kappa Sigma Games';
	$body = $name.",<br /><br />";
	$body .= "<head><body>Thank you for registering for the Second Annual Kappa Sigma Games: Kick the Stigma! We're excited for a fun day of games and activities while helping a great cause, and we hope you are too!<br /><br />";
	$body .= "A few reminders before the event: <br /><br />";
	$body .= "1) Remember to pay before the event! This can be done at our website (http://kappasigmagames.com) and must be completed prior to the event. Be sure to make sure your teammates have paid as well! <br /><br />";
	$body .= "2) OPEN the attached Google Form and agree to our Liability Waiver <a href='https://docs.google.com/forms/d/1dfCGMcJ5EsFvkdOPYe7WUsWnlTXcCJpRJrv7szdkrPg/viewform'>here</a> for the event. Once again, this must be completed by ALL players for your team to be eligible to compete. <br /><br />";
	$body .= "3) Registration for the event begins on Tech Green at 10:00 AM on Sunday, November 23, with Opening Ceremonies becoming at 10:45 AM.  Please be on time to guarantee the event will be completed prior to sunset.<br /><br />";
	$body .= "4) Feel free to donate additionally to the American Foundation for Suicide Prevention by clicking the Donate button under the Participation tab on our website!<br />";
	$body .= "5) COME WITH YOUR GAME FACE ON!<br /><br />";
	$body .= "If you have any questions or concerns, feel free to contact us at kappasigmagames@gmail.com.  Once again, thank you again for playing, and we'll see you on November 23rd!  Also, check us out on <a href='https://www.facebook.com/kskickthestigma'>Facebook!</a><br /><br />";
	$body .= "-The Kappa Sigma Games Squad</body></head>";

	$headers = array(
		'From' => $from,
		'To' => $to,
		'Subject' => $subject,
		'Content-type' =>'text/html; charset=utf-8'
	);
	
	$smtp = Mail::factory('smtp', array(
			'host' => 'ssl://smtp.gmail.com',
			'port' => '465',
			'auth' => true,
			'username' => 'kappasigmagames@gmail.com',
			'password' => 'kappasigma1123'
		));
	
	$mail = $smtp->send($to, $headers, $body);
	
	if (PEAR::isError($mail)) {
		echo('<p>' . $mail->getMessage() . '</p>');
	}
}

function createTeam($name, $adminId) {
	mysql_query("INSERT INTO `teams_2015` (`name`, `admin_id`) values ('$name', '$adminId')") or die(mysql_error());
	return mysql_insert_id();
}

function joinTeam($id, $teamId) {
	mysql_query("UPDATE `players_2015` SET `team_id` = '$teamId' WHERE `id` = '$id';") or die(mysql_error());
}

function isTeamUsed($name) {
 $result = mysql_query("SELECT * FROM `teams_2015` WHERE `name` = '$name'") or die(mysql_error()); 
 $num_rows = mysql_num_rows($result);
 if ($num_rows > 0) {
 	return 1;
 } else {
	return 0;
 }
}


function logout($redirect) {
	setcookie('email', "", 0); 
	setcookie('password', "", 0); 
	header('Location: '.$redirect);
}

function getTeam($teamId) {
	$result = mysql_query("SELECT * FROM `teams_2015` WHERE `id` = '$teamId'") or die(mysql_error()); 
	$row = mysql_fetch_array($result);  	
	return $row;
}

function getTeamMemberArray($teamId) {
	$result = mysql_query("SELECT * FROM `players_2015` WHERE `team_id` = '$teamId'") or die(mysql_error()); 
	return $result;
}

function getAllUsers() {
	$result = mysql_query("SELECT *, players_2015.name as userName FROM `players_2015` LEFT JOIN `teams_2015` on players_2015.team_id = teams_2015.id ORDER BY  players_2015.name") or die(mysql_error()); 
	return $result;	
}

function getAllTeams() {
	$result = mysql_query("SELECT * FROM `teams_2015` GROUP BY name ORDER BY  name") or die(mysql_error()); 
	return $result;	
}

function isTeamValid($teamId) {
	$result = mysql_query("SELECT * FROM `teams_2015` WHERE `id` = '$teamId'") or die(mysql_error());
	return (mysql_num_rows($result) > 0);
}

function getTeamCount($teamId) {
	$result = mysql_query("SELECT * FROM `players_2015` WHERE `team_id` = '$teamId'") or die(mysql_error()); 
	$num_rows = mysql_num_rows($result);
 	return $num_rows;
}

function getTeamCountPaid($teamId) {
	$result = mysql_query("SELECT * FROM `players_2015` WHERE `team_id` = '$teamId' AND `paid` = '1'") or die(mysql_error()); 
	$num_rows = mysql_num_rows($result);
 	return $num_rows;
}

function getUserCount() {
	$result = mysql_query("SELECT * FROM `players_2015`") or die(mysql_error()); 
	$num_rows = mysql_num_rows($result);
 	return $num_rows;
}

function getPaidCount() {
	$result = mysql_query("SELECT * FROM `players_2015` WHERE `paid` = '1'") or die(mysql_error()); 
	$num_rows = mysql_num_rows($result);
 	return $num_rows;
}

function getTotalTeamCount() {
	$result = mysql_query("SELECT * FROM `teams_2015`") or die(mysql_error()); 
	$num_rows = mysql_num_rows($result);
 	return $num_rows;
}
 
function cleanInput($string) {
  $string = trim($string); // Strip whitespace from the beginning of a string.
  $string = str_replace('<?php', 'fail', $string); 
  $string = str_replace('mysql', 'fail', $string); 
   $string = str_replace('query(', 'fail', $string); 
  /*$string = strip_tags($string); // Strip HTML and PHP tags. */
  if (!get_magic_quotes_gpc()) { // Returns 0 if magic_quotes_gpc is off, 1 otherwise.
   $string = addslashes($string);
  }
  $string = rtrim($string); // Strip whitespace from the end of a string.
  return $string; // Return the final string.
 } 
 
 function convertLink($link) {
 echo getConvertedLink($link); 
}

function getConvertedLink($link) {
$i = substr_count($_SERVER['PHP_SELF'], '/') - 1; // 2
if ($i == "1") {
	return "../".$link;
} else if ($i == "2") {
	return "../../".$link;
} else if ($i == "3") {
	return "../../../".$link;
} else if ($i == "4") {
	return "../../../../".$link;
} else
return $link;
}

function checkEmail($email) { 
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) { 
    return 0;
  } 
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return 0;
    }
  } 
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return 0; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return 0;
      }
    }
  }
  return 1;
}

function getEnumValues( $table , $field ){ 
        $query = " SHOW COLUMNS FROM `".$table."` LIKE '".$field."' ";
        $result = mysql_query( $query ) or die( 'error getting enum field ' . mysql_error() ); 
        $row = mysql_fetch_array( $result , MYSQL_NUM ); 
        $regex = "/'(.*?)'/"; 
        //$regex = "/'[^"\\\r\n]*(\\.[^"\\\r\n]*)*'/"; 
        preg_match_all( $regex , $row[1], $enum_array ); 
        $enum_fields = $enum_array[1]; 
        return( $enum_fields ); 
}  
?>