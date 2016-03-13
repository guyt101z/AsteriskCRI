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
	$buffer=str_replace("%TITLE%","Audit Log",$buffer);
	echo $buffer;
?>

<?php 
	$auditdetails = "No additional parameters";
	WriteAuditEvent("Audit Log","Viewed audit log",$auditdetails);
?>

<?php 
include('include/dbconnect.inc.php');
$result = mysql_query("SELECT * FROM audit ORDER BY eventid ASC", $db_cri);
?>

<div class="container">
	<div class="page-header">
		<h1>Audit Log</h1>
	</div>
	<table class="table table-hover">
		<thead>
			<th>Event ID</th>
			<th>Module</th>
			<th>Event</th>
			<th>Details</th>
			<th>User</th>
			<th>IP Address</th>
			<th>Timestamp</th>
		</thead>
		<tbody>
			<?php
				if(mysql_num_rows($result) == 0)
				{
					echo "<tr>";
					echo "<td colspan=\"7\">There is no data to display.</td>";
					echo "</tr>\n";
				}
				else
				{
					while($row = mysql_fetch_array($result))
					{
						echo "<td>".$row['eventid']."</td>";
						echo "<td>".$row['module']."</td>";
						echo "<td>".$row['event']."</td>";
						echo "<td>".$row['details']."</td>";
						echo "<td>".$row['user']."</td>";
						echo "<td>".$row['ip_address']."</td>";
						echo "<td>".$row['timestamp']."</td>";
						echo "</tr>\n";
					}
				}
			?>
		</tbody>
	</table>
</div>

<?php
	mysql_close();
	include('include/footer.inc.php');
?>