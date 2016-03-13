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

<!-- script for autio player popup -->
<script language="javascript" type="text/javascript">
<!-- //
var win=null;
function NewWindow(mypage,myname,w,h,scroll,pos){
if(pos=="random"){LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;}
if(pos=="center"){LeftPosition=(screen.width)?(screen.width-w)/2:100;TopPosition=(screen.height)?(screen.height-h)/2:100;}
else if((pos!="center" && pos!="random") || pos==null){LeftPosition=0;TopPosition=20}
settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
win=window.open(mypage,myname,settings);
}
// -->
</script>

<?php
	ob_start();
	include("include/header.inc.php");
	$buffer=ob_get_contents();
	ob_end_clean();
	$buffer=str_replace("%TITLE%","Flagged calls",$buffer);
	echo $buffer;
?>

<?php include('include/dbconnect.inc.php'); ?>

<?php
	if (isset($_GET['view'])) {
		$view = $_GET['view'];
	} else {
		$view = "all";
	}
	
	if ($view == "mine") {
		$me = $_SESSION['username'];
		$result = mysql_query("SELECT * FROM flagged WHERE flag_by LIKE '$me'", $db_cri);
	} else if ($view = "all") {
		$result = mysql_query("SELECT * FROM flagged", $db_cri);
	}
?>

<?php 
	$auditdetails = "Viewed flagged calls list".$date;
	WriteAuditEvent("Call Detail Report","CDR View",$auditdetails);
?>

<div class="container">
	<div class="page-header">
		<h1>Flagged calls</h1>
	</div>
		
	<div class="panel panel-default">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Date/Time</th>
					<th>Source</th>
					<th>Destination</th>
					<th>Result</th>
					<th>Duration</th>
					<th>Reason</th>
					<th>Flag by</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				
					while ($flagresult = mysql_fetch_array($result))
					{
						$id = $flagresult['call_id'];
						$row = mysql_fetch_array(mysql_query("SELECT * FROM cdr WHERE uniqueid LIKE '$id'", $db_asterisk));
						
						if ($row['duration'] >= "3600") {
							$dur = gmdate("H:i:s", $row['duration']);
						}
						else {
							$dur = gmdate("i:s", $row['duration']);
						}
						
						$actionbuttons = "<a href=\"call.php?id=".$row['uniqueid']."\" onclick=\"NewWindow(this.href,'Call Information','800','500','no','center');return false\" onfocus=\"this.blur()\"><i class=\"fa fa-plus-square fa-1x\"></i></a>&nbsp;&nbsp;<a href=\"cdrflag_remove.php?id=".$row['uniqueid']."\" onclick=\"NewWindow(this.href,'RemoveFlag','800','360','no','center');return false\" onfocus=\"this.blur()\"><i class=\"fa fa-times fa-1x\"></i></a>";
						
						echo "<tr>";
						echo "<td>".$row['calldate']."</td>";
						echo "<td>".$row['src']."</td>";
						echo "<td>".$row['dst']."</td>";
						echo "<td>".$row['disposition']."</td>";
						echo "<td>".$dur."</td>";
						echo "<td>".$flagresult['flag_reason']."</td>";
						echo "<td>".$flagresult['flag_by']."</td>";
						echo "<td>".$actionbuttons."</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php include ('include/footer.inc.php'); ?>