<?php
	function WriteAuditEvent($module,$event,$details) {
		include('include/dbconnect.inc.php');
		$user = $_SESSION['username'];
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$audit_query = mysql_query("INSERT INTO audit (module, event, details, user, ip_address) VALUES ('$module','$event','$details','$user','$ip_address')", $db_cri);
		mysql_close;
		return 1;
	}
?>