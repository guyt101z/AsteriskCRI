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
session_start()
$callid = $_GET['callid']
$user = $_SESSION['username'];

mysql_query("INSERT INTO flagged (call_id, flag_by, flag_reason) VALUES ('$callid','$user','1')", $db_cri) or die(mysql_error());

echo mysql_error();
?>