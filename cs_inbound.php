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
win=window.open(mypage,myname,settings);}
// -->
</script>

<?php
	ob_start();
	include("include/header.inc.php");
	$buffer=ob_get_contents();
	ob_end_clean();
	$buffer=str_replace("%TITLE%","Customer Service Inbound Calls",$buffer);
	echo $buffer;
?>

<?php include('include/dbconnect.inc.php'); ?>

<?php 
	$dest = $cs_inbound_dest;
	//
	if (isset($_GET['order'])) {
		$order = $_GET['order'];
	}
	else {
		$order = "DESC";
	}
	//
	if (isset($_GET['date'])) {
		if ($_GET['date'] == "") {
			$date = date("Y-m-d");
		}
		else {
			$date = $_GET['date'];
		}
	}
	else {
		$date = date("Y-m-d");
	}
	//$querydatefrom = strtotime("-1 day", $datefrom);
	//$querydateto = strtotime("+1 day", $dateto);
	$result = mysql_query("SELECT * FROM cdr WHERE dst LIKE '$dest' AND calldate BETWEEN '$date' AND DATE_ADD('$date', INTERVAL 1 DAY) ORDER BY calldate $order", $db_asterisk);
	
?>

<?php 
	$auditdetails = "Viewed customer service inbound calls for ".$date;
	WriteAuditEvent("Call Detail Report","CDR View",$auditdetails);
?>

<div class="container">
	<div class="page-header">
		<h1>Customer Service inbound calls on <?php echo date("l dS M Y", strtotime($date)); ?></h1>
	</div>
	
	<form class="form-inline" action="cs_inbound.php" method="GET">
		<fieldset>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">Date</div>
					<input class="form-control" type="text" name="date" value="<?php echo $date; ?>">
				</div>
			</div>
			<button class="btn btn-primary" type="submit">Submit</button>
		</fieldset>
	</form>
		
	<div class="panel panel-default">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Date/Time</th>
					<th>Caller ID</th>
					<th>Result</th>
					<th>Duration</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$totalresults = 0;
					$totalduration = 0;
				
					while ($row = mysql_fetch_array($result))
					{
						$totalresults = $totalresults + 1;
						$totalduration = $totalduration + $row['duration'];
						
						if ($row['duration'] >= "3600") {
							$dur = gmdate("H:i:s", $row['duration']);
						}
						else {
							$dur = gmdate("i:s", $row['duration']);
						}
						
						$actionbuttons = "<a href=\"call.php?id=".$row['uniqueid']."\" onclick=\"NewWindow(this.href,'Call Information','800','500','no','center');return false\" onfocus=\"this.blur()\"><i class=\"fa fa-plus-square fa-1x\"></i></a>&nbsp;&nbsp;<a href=\"cdrflag.php?id=".$row['uniqueid']."\" onclick=\"NewWindow(this.href,'Flag Call','800','400','no','center');return false\" onfocus=\"this.blur()\"><i class=\"fa fa-flag fa-1x\"></i></a>";
						
						echo "<tr>";
						echo "<td>".$row['calldate']."</td>";
						echo "<td>".$row['src']."</td>";
						echo "<td>".$row['disposition']."</td>";
						echo "<td>".$dur."</td>";
						echo "<td>".$actionbuttons."</td>";
						echo "</tr>";
					}
				?>
			</tbody>
		</table>
	</div>
	
	<?php
		if ($totalduration >= "3600") {
			$totaldurationformatted = gmdate("H:i:s", $totalduration);
		}
		else {
			$totaldurationformatted = gmdate("i:s", $totalduration);
		}
	?>
	
	<div class="panel panel-default">
		<div class="panel-body">
			<p>Total calls: <strong><?php echo $totalresults; ?></strong></p>
			<p>Total duration: <strong><?php echo $totaldurationformatted; ?></strong></p>
		</div>
	</div>
</div>
<?php include ('include/footer.inc.php'); ?>