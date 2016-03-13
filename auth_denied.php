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
session_start();
if ($_SESSION['loggedin'] != true) {
	$authredirect = "Location: auth.php?action=login";
    header($authredirect);
    die();
}

include('include/dbconnect.inc.php');
include('include/functions.php');
WriteAuditEvent("Auth","Login denied","User not authorized to access application",$_SESSION['username']);
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
		<title>CRI - Access Denied</title>
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
				background-color: #fff;
				border: 1px solid #e5e5e5;
				-webkit-border-radius: 5px;
				   -moz-border-radius: 5px;
						border-radius: 5px;
				-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
						box-shadow: 0 1px 2px rgba(0,0,0,.05);
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
      			background-image: linear-gradient(#e72510, #d9230f 6%, #cb210e); }
      		@media (max-width: 480px) { 
    			table {
          			font-size: 0.8em;
        		}
        	}
		</style>
	</head>
	<body>
		<div class="container">			
			<div class="form-signin">
				<div style="border-bottom: 1px solid #dddddd; padding: 10px;">
				<p><img src="images/logo.png" alt="Logo" width="200px"></p>
				</div>
				<h4 class=\"form-signin-heading\">Access Denied</h2>
				<p>You have signed in successfully, but you are not allowed to access this application. This incident will be reported.</p>
				<p>If you believe this is an error, please see <a href="#" target="_blank">here</a>.</p>
				<p><strong>DETAILS OF VIOLATION</strong><br>
					<strong>Username:</strong> <?php echo $_SESSION['username']; ?><br>
					<strong>Date/Time:</strong> <?php echo date("Y-m-d H:i:s"); ?><br>
					<strong>Application:</strong> Workforce<br>
					<strong>Reason:</strong> Incorrect job code</p>
					<br>
				<a href="auth.php?action=logout"><button class="btn btn-primary">Log out</button></a>
			</div>
		</div> <!-- /container -->
		
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>