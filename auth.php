<!--
	AsteriskCRI
    Copyright (C) 2012-2016  Fraser Jamieson <fj@fraser.re>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 -->

<?php
include('include/dbconnect.inc.php');
include('include/functions.php');
include('include/settings.inc.php');

if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = "login";
}

//log them out
$logout = $_GET['logout'];
if ($logout == "yes") { //destroy the session
	session_start();
	$_SESSION = array();
	session_destroy();
}

//force the browser to use ssl (STRONGLY RECOMMENDED!!!!!!!!)
//if ($_SERVER["SERVER_PORT"] != 443){ 
//    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']); 
//    exit(); 
//}

//you should look into using PECL filter or some form of filtering here for POST variables
$username = $_POST["username"]; //remove case sensitivity on the username
$password = $_POST["password"];
$formage = $_POST["formage"];

if ($_POST["oldform"]) { //prevent null bind

	if ($username != NULL && $password != NULL){
		//include the class and create a connection
		include (dirname(__FILE__) . "/ldap/adLDAP.php");
        try {
		    $adldap = new adLDAP();
        }
        catch (adLDAPException $e) {
            echo $e; 
            exit();   
        }
		
		//authenticate the user
		if ($adldap->authenticate($username, $password)){
			if (in_array($username, $ad_allowed_users)) {
    			//establish your session and redirect
				session_start();
				$_SESSION['loggedin'] = true;
				$_SESSION['username'] = $username;
				$_SESSION['userinfo'] = $adldap->user()->info($username);
			
				WriteAuditEvent("Auth","Login success","User logged in successfully");
			
				$ingroup = $adldap->user()->inGroup($username,"doola");
				$_SESSION['ingroup'] = $ingroup;
				echo $_SESSION['ingroup'];
				$_SESSION['fish'] = "yes!";
			
				include('/include/dbconnect.inc.php');
			
			
				if (isset($_POST['redirect']) && $_POST['redirect'] != "") {
					$redir = "Location: ".$_POST['redirect'];
				} else {
					$redir = "Location: /index.php";
				}
				header($redir);
				exit;
			}
			else {
				session_start();
				$_SESSION['loggedin'] = true;
				$_SESSION['username'] = $username;
				$_SESSION['userinfo'] = $adldap->user()->info($username);
				WriteAuditEvent("Auth","Login denied","User authentication failed: user is not allowed to access this application");
				setcookie(session_name(), '', 100);
				session_unset();
				session_destroy();
				$_SESSION = array();
				$denied = 1;
				mail("secadmin@jamiesonmotors.co.uk","Login denied event","This is the messsssage!","From: root@web01.lan.jamiesonmotors.co.uk");
			}
		}
		else {
			$failed = 1;
			WriteAuditEvent("Auth","Login failure","User authentication failed: incorrect credentials");
		}
	}
}

if ($action == "logout") {
	session_start();
	WriteAuditEvent("Auth","Logout","User logged out");
	setcookie(session_name(), '', 100);
	session_unset();
	session_destroy();
	$_SESSION = array();
	$logout = "yes";
}

?>
<html>
	<head>
		<script type="text/javascript">
			if(("standalone" in window.navigator) && window.navigator.standalone){

			var noddy, remotes = false;

			document.addEventListener('click', function(event) {

			noddy = event.target;

			while(noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
			noddy = noddy.parentNode;
			}

			if('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes))
			{
			event.preventDefault();
			document.location.href = noddy.href;
			}

			},false);
			}
		</script>
		<title>CRI - <?php if ($action == "login") { echo "Log in"; } else if ($action == "logout") { echo "Log out"; } else { echo "Session info"; } ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="img/favicon.ico">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link type="text/css" href="css/bootstrap-datetimepicker.min.css" />
		<!-- setup for apple ios devices -->
		<link rel="apple-touch-icon" href="img/apple-icon.png"/>
		<link rel="apple-touch-startup-image" href="img/apple-splash.png" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<!-- end ios setup -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		 <style>
			.alert {
				max-width: 400px;
				margin: 0 auto 20px;
			}
			.form-signin {
				max-width: 400px;
				padding: 19px 29px 29px;
				margin: 0 auto 20px;
				background-color: #3e3f3a;
				border: 1px solid #e5e5e5;
				-webkit-border-radius: 5px;
				   -moz-border-radius: 5px;
						border-radius: 5px;
				-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
						box-shadow: 0 1px 2px rgba(0,0,0,.05);
			}
			.footer {
				max-width: 400px;
				padding: 19px 29px 29px;
				margin: 0 auto 20px;
				color: #fff;
				text-align: center;
			}
			.form-signin .form-signin-heading,
			.form-signin .checkbox {
				margin-top: 20px;
				margin-bottom: 20px;
			}
			.form-signin input[type="text"],
			.form-signin input[type="password"] {
				font-size: 16px;
				height: auto;
				margin-bottom: 15px;
				padding: 7px 9px;
			}
			body {
      			padding-top: 60px;
      			background: url(images/login_bg.jpg) no-repeat center center fixed; }
      		@media (max-width: 480px) { 
    			table {
          			font-size: 0.8em;
        		}
        	}
        	h4 {
        		color: #fff;
        	}
		</style>
	</head>
	<body>
		<div class="container">			
			<?php if ($action == "login" || $action == "logout") {
				if ($failed) { echo "<div class=\"alert alert-warning\"><strong>ERROR</strong><br>Username and/or password incorrect<br><a href=\"#\" target=\"_blank\">Forgot your password?</a></div>"; }
				if ($denied) { echo "<div class=\"alert alert-danger\"><strong>ACCESS DENIED</strong><br>You are not allowed to access this application.<br>This incident will be reported.<br><a target=\"_blank\" href=\"#\">Click here for help</a></div>"; }
				if ($logout == "yes") { echo "<div class=\"alert alert-success\">You have successfully logged out</div>"; }
				echo "<form method=\"post\" action=\"auth.php?redirect=".$_GET['redirect']."\" class=\"form-signin\">
						<input type=\"hidden\" name=\"oldform\" value=\"1\">
						<input type=\"hidden\" name=\"redirect\" value=\"".$_GET['redirect']."\">
						<div style=\"border-bottom: 1px solid #dddddd; padding: 10px;\">
						<p><center><img src=\"images/cri_logo.png\" alt=\"CRI Logo\" width=\"200px\"></center></p>
						</div>
						";
						if ($action != "logout"){echo "<h4 class=\"form-signin-heading\"><center>Please log in to continue</center></h2>";} else { echo "<br>"; }
						echo "
						<input type=\"text\" class=\"form-control\" placeholder=\"Username\" name=\"username\" value=\"".$username."\">
						<input type=\"password\" class=\"form-control\" placeholder=\"Password\" name=\"password\">
						<button style=\"width: 100%;\" class=\"btn btn-large btn-primary\" type=\"submit\">Log in</button>
					</form>";
			}
			?>
		</div> <!-- /container -->
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>