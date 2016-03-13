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

	$uniqueid = $_POST['uniqueid'];
	$flag = $_POST['flag'];

	$query = mysql_query("DELETE FROM flagged WHERE flag_id = '$flag' LIMIT 1", $db_cri);

	

	if (!$query) {
		$redirect = "Location: error.php?e=".mysql_error();
		header($redirect);
		die();
	}
	else {
		$details = "Deleted flag on call ".$uniqueid;
		WriteAuditEvent("CDR","Flag call",$details);
		echo "<script>window.opener.location.reload(true);
        window.close();</script>";
	}
?>