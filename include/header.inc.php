<?php

include('include/settings.inc.php');
include('include/functions.php');
include('include/dbconnecct.inc.php');

session_start();

if ($_SESSION['loggedin'] != true) {
	$authredirect = "Location: auth.php?action=login&redirect=".$_SERVER['PHP_SELF'];
    header($authredirect);
    die();
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>CRI - %TITLE%</title>
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
    	body {
    	padding-top: 60px; }
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

<!--start navbar-->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="index.php"><img src="images/cri_logo.png" height="25px"></a>
	</div>
	<div class="navbar-collapse collapse">
	  <ul class="nav navbar-nav">
		<li <?php if($_SERVER['PHP_SELF'] == "/cri/index.php") {echo " class=\"active\"" ;} ?>><a href="index.php">Dashboard</a></li>
		<li class="dropdown<?php if($_SERVER['PHP_SELF'] == "/cri/cdr.php" || $_SERVER['PHP_SELF'] == "/cri/cs_inbound.php" || $_SERVER['PHP_SELF'] == "/cri/bar.php") {echo " active" ;} ?>">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Call Reports <span class="caret"></span></a>
		  <ul class="dropdown-menu" role="menu">
		  	<li <?php if($_SERVER['PHP_SELF'] == "/cri/cs_inbound.php") {echo " class=\"active\"" ;} ?>><a href="cs_inbound.php">Customer Service Inbound Calls</a></li>
		  	<li class="divider">
			<li <?php if($_SERVER['PHP_SELF'] == "/cri/cdr.php") {echo " class=\"active\"" ;} ?>><a href="cdr.php">CDR Search</a></li>
			<li class="divider">
			<li <?php if($_SERVER['PHP_SELF'] == "/cri/flagged.php") {echo " class=\"active\"" ;} ?>><a href="flagged.php">Flagged calls</a></li>
		  </ul>
		</li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Management Reports <span class="caret"></span></a>
		  <ul class="dropdown-menu" role="menu">
			<li><a href="#">Call wait time</a></li>
			<li><a href="#">Call volume</a></li>
			<li><a href="#">Call wait time</a></li>
		  </ul>
		</li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <span class="caret"></span></a>
		  <ul class="dropdown-menu" role="menu">
		  	<li><a href="about.php">About CRI</a></li>
			<li><a href="audit_log.php">Audit log</a></li>
		  </ul>
		</li>
	  </ul>
	  <ul class="nav navbar-nav navbar-right">
	    <li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong>User:</strong> <?php echo $_SESSION['username']; ?> <span class="caret"></span></a>
		  <ul class="dropdown-menu" role="menu">
			<li><a target="_blank" href="<?php echo $profile_url.$_SESSION['username']; ?>">User profile</a></li>
			<li><a href="auth.php?action=logout">Log out</a></li>
		  </ul>
		</li>
	  </ul>
	</div><!--/.nav-collapse -->
  </div>
</div>