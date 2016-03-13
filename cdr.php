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
	$buffer=str_replace("%TITLE%","Call Detail Records",$buffer);
	echo $buffer;
?>

<?php include('include/dbconnect.inc.php'); ?>

<?php 
	if (isset($_GET['order'])) {
		$order = $_GET['order'];
	}
	else {
		$order = "DESC";
	}
	//
	if (isset($_GET['source'])) {
		if ($_GET['source'] == "") {
			$source = "%";
		}
		else {
			$source = $_GET['source'];
		}
	}
	else {
		$source = "%";
	}
	//
	if (isset($_GET['dest'])) {
		if ($_GET['dest'] == "") {
			$dest = "%";
		}
		else {
			$dest = $_GET['dest'];
		}
	}
	else {
		$dest = "%";
	}
	//
	if (isset($_GET['datefrom'])) {
		if ($_GET['datefrom'] == "") {
			$datefrom = date("Y-m-d");
		}
		else {
			$datefrom = $_GET['datefrom'];
		}
	}
	else {
		$datefrom = date("Y-m-d");
	}
	//
	if (isset($_GET['dateto'])) {
		if ($_GET['dateto'] == "") {
			$dateto = date("Y-m-d");
		}
		else {
			$dateto = $_GET['dateto'];
		}
	}
	else {
		$dateto = date("Y-m-d");
	}
	//
	if (isset($_GET['callresult'])) {
		if ($_GET['callresult'] == "All")
		{
			$callresult = "%";
		}
		else if ($_GET['callresult'] == "noanswer")
		{
			$callresult = "No answer";
		}
		else {
			$callresult = $_GET['callresult'];
		}
	}
	else {
		$callresult = "Answered";
	}
	//$querydatefrom = strtotime("-1 day", $datefrom);
	//$querydateto = strtotime("+1 day", $dateto);
	$result = mysql_query("SELECT * FROM cdr WHERE src LIKE '$source' AND dst LIKE '$dest' AND disposition LIKE '$callresult' AND calldate BETWEEN '$datefrom' AND DATE_ADD('$dateto', INTERVAL 1 DAY) ORDER BY calldate $order", $db_asterisk);
	
?>

<div class="container">
	<div class="page-header">
		<h1>CDR Search</h1>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			Search
		</div>
		<div class="panel-body">
			<div class="container">
				<form class="form-horizontal" action="cdr.php" method="GET">
					<fieldset>
						<div class="col-lg-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="tokenName">Date From</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="datefrom" value="<?php if ($datefrom <> "%") { echo $datefrom; } ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="tokenName">Date To</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="dateto" value="<?php if ($dateto <> "%") { echo $dateto; } ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="tokenName">Call Result</label>
								<div class="col-md-6">
									<select class="form-control" name="callresult">
										<option <?php if ($callresult == "%") { echo "selected=\"selected\""; } ?> value="%">All</option>
										<option <?php if ($callresult == "answered") { echo "selected=\"selected\""; } ?> value="answered">Answered</option>
										<option <?php if ($callresult == "No answer") { echo "selected=\"selected\""; } ?> value="noanswer">No answer</option>
										<option <?php if ($callresult == "failed") { echo "selected=\"selected\""; } ?> value="failed">Failed</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="tokenName">Call Source</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="source" value="<?php if ($source <> "%") { echo $source; } ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label" for="tokenName">Call Destination</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="dest" value="<?php if ($dest <> "%") { echo $dest; } ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6">
									<button class="btn btn-primary" type="submit">Search</button>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
			
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="container">
				<div class="col-md-2">
					<div class="input-group">
						<span class="input-group-addon">Sort</span>
						<div class="btn-group">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								<?php echo $order; ?> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu">
								<li><a href="cdr.php?order=ASC&source=<?php echo $source; ?>&dest=<?php echo $dest; ?>&callresult=<?php
								if ($callresult == "%") { echo "All"; } else { echo $callresult; } ?>&datefrom=<?php echo $datefrom; ?>&dateto=<?php echo $dateto; ?>">ASC</a>
								</li>
								<li><a href="cdr.php?order=DESC&source=<?php echo $source; ?>&dest=<?php echo $dest; ?>&callresult=<?php
								if ($callresult == "%") { echo "All"; } else { echo $callresult; } ?>">DESC</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div><!--panel-heading-->
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Date/Time</th>
					<th>Caller ID</th>
					<th>Source</th>
					<th>Destination</th>
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
						echo "<td>".$row['cnam']."</td>";
						echo "<td>".$row['src']."</td>";
						echo "<td>".$row['dst']."</td>";
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
			<p>Total results: <strong><?php echo $totalresults; ?></strong></p>
			<p>Total duration: <strong><?php echo $totaldurationformatted; ?></strong></p>
		</div>
	</div>
</div>
<?php include ('include/footer.inc.php'); ?>
<!--write the audit event for search-->
<?php 
$auditdetails = "From: ".$datefrom."\r\nTo: ".$dateto."\r\nSrc: ".$src."\r\nDst: ".$dst."\r\nOrder: ".$order."\r\nFilter: ".$callresult;
WriteAuditEvent("Call Detail Report","CDR Search",$auditdetails);
?>