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

include('include/settings.inc.php');
include('include/functions.php');

session_start();

if ($_SESSION['loggedin'] != true) {
	$authredirect = "Location: auth.php?action=login";
    header($authredirect);
    die();
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>CRI - Remove flag</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="images/favicon.ico">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link href="css/datepicker.css" rel="stylesheet">
		<!-- setup for apple ios devices -->
		<link rel="apple-touch-icon" href="images/apple-icon.png"/>
		<link rel="apple-touch-startup-image" href="images/apple-splash.png" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<!-- end ios setup -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
    	<style>
			@media (max-width: 480px) { 
				table { font-size: 0.8em; }
			}
		</style>

		<script language="javascript" type="text/javascript">
	 		function resizeIframe(obj) {
 	  			obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
 			}
		</script>

		<!-- script to fix ios hyperlink behaviour -->
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
	</head>
  
	<body>
		<?php
			include('include/dbconnect.inc.php');
			$cid = $_GET['cid'];
			$fid = $_GET['fid'];
			$cdrresult = mysql_fetch_array(mysql_query("SELECT * FROM cdr WHERE uniqueid LIKE '$id'", $db_asterisk));
		?>
		<div class="container">
			<div class="page-header">
				<h1>Remove flag</h1>
			</div>
			<form action="cdrflag_remove_insert.php" method="POST">
				<input type="hidden" id="uniqueid" name="uniqueid" value="<?php echo $cid; ?>">
				<input type="hidden" id="flag" name="flag" value="<?php echo $fid; ?>">
				<div class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-6">Are you sure you want to remove this flagged call?</div>
						<div class="col-sm-2">
							<input class="btn btn-success" type="submit" value="Yes">
						</div>
					</div>
				</div>
			</form>
			<div class="panel panel-primary">
				<div class="panel-heading">
					Call details
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-5">
							<div class="col-sm-4">
								<strong>Call ID:</strong>
							</div>
							<div class="col-sm-8">
								<?php echo $id; ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="col-sm-4">
								<strong>Source:</strong>
							</div>
							<div class="col-sm-8">
								<?php echo $cdrresult['src']; ?>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="col-sm-5">
								<strong>Length:</strong>
							</div>
							<div class="col-sm-7">
								<?php
									if ($cdrresult['duration'] >= "3600") {
										$dur = gmdate("H:i:s", $cdrresult['duration']);
									}
									else {
										$dur = gmdate("i:s", $cdrresult['duration']);
									}
									echo $dur;
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<div class="col-sm-4">
								<strong>Date:</strong>
							</div>
							<div class="col-sm-8">
								<?php echo $cdrresult['calldate']; ?>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="col-sm-4">
								<strong>Dest:</strong>
							</div>
							<div class="col-sm-8">
								<?php echo $cdrresult['dst']; ?>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="col-sm-5">
								<strong>Result:</strong>
							</div>
							<div class="col-sm-7">
								<?php echo $cdrresult['disposition']; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>