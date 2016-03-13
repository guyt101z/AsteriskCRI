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
	ob_start();
	include("include/header.inc.php");
	$buffer=ob_get_contents();
	ob_end_clean();
	$buffer=str_replace("%TITLE%","Error",$buffer);
	echo $buffer;
	
	if (isset($_GET['e'])) { $error = $_GET['e']; }
	
	WriteAuditEvent("System","Error","Error message: ".$error);
?>

<div class="container">
	<div class="page-header">
		<h1><strong><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Fatal error</strong></h1>
	</div>
	<pre><strong>CRI encountered a fatal error while trying to process your request.</strong> The request did not complete and will not be retried by the system. The exact error message is printed below.</pre>
	<br><br>
	<code><?php echo $error; ?></code>
	<br><br>
	Please report this error by opening a new support ticket <a href="#">here</a>. Include details of what you were trying to do when the error occurred and copy and paste the error details shown above in to your ticket.
	<br><br>
	<strong>Do not use your broswer's back button!</strong> Click <a href="index.php">here</a> to return to the CRI dashboard.
</div>
<?php include('include/footer.inc.php'); ?>