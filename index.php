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
	$buffer=str_replace("%TITLE%","Dashboard",$buffer);
	echo $buffer;

	// date vars
	$today = date("Y-m-d");
	$yesterday = date("Y-m-d", time() - 60 * 60 * 24);

	// mysql queries
	$todaycalls = mysql_query("SELECT * FROM cdr WHERE calldate BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 1 DAY)");
	$todaycallsanswered = mysql_query("SELECT * FROM cdr WHERE disposition = 'ANSWERED' AND calldate BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 1 DAY)");
	$yesterdaycalls = mysql_query("SELECT * FROM cdr WHERE calldate BETWEEN '$yesterday' AND DATE_ADD('$yesterday', INTERVAL 1 DAY)");
	$yesterdaycallsanswered = mysql_query("SELECT * FROM cdr WHERE disposition = 'ANSWERED' AND calldate BETWEEN '$yesterday' AND DATE_ADD('$yesterday', INTERVAL 1 DAY)");
	$todaycallsnoanswer = mysql_query("SELECT * FROM cdr WHERE disposition = 'NO ANSWER' AND calldate BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 1 DAY)");
	$yesterdaycallsnoanswer = mysql_query("SELECT * FROM cdr WHERE disposition = 'NO ANSWER' AND calldate BETWEEN '$yesterday' AND DATE_ADD('$yesterday', INTERVAL 1 DAY)");
	$todaycallsfailed = mysql_query("SELECT * FROM cdr WHERE disposition = 'FAILED' AND calldate BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 1 DAY)");
	$yesterdaycallsfailed = mysql_query("SELECT * FROM cdr WHERE disposition = 'FAILED' AND calldate BETWEEN '$yesterday' AND DATE_ADD('$yesterday', INTERVAL 1 DAY)");


	// call volume calcs
	$today_numcalls = mysql_num_rows($todaycalls);
	$yesterday_numcalls = mysql_num_rows($yesterdaycalls);
	
	// call time calcs
	while ($row1 = mysql_fetch_array($todaycalls)) {
		$today_calltime = $today_calltime + $row1['duration'];
	}
	while ($row2 = mysql_fetch_array($yesterdaycalls)) {
		$yesterday_calltime = $yesterday_calltime + $row2['duration'];
	}
	$today_calltime = gmdate("H:i:s", $today_calltime);
	$yesterday_calltime = gmdate("H:i:s", $yesterday_calltime);
	
	// answered calcs
	$today_answered = mysql_num_rows($todaycallsanswered);
	$yesterday_answered = mysql_num_rows($yesterdaycallsanswered);
	
	// noanswer calcs
	$today_noanswer = mysql_num_rows($todaycallsnoanswer);
	$yesterday_noanswer = mysql_num_rows($yesterdaycallsnoanswer);
	
	// failed calcs
	$today_failed = mysql_num_rows($todaycallsfailed);
	$yesterday_failed = mysql_num_rows($yesterdaycallsfailed);
	
?>


	

<div class="container">
	<div class="page-header">
		<h1>Dashboard</h1>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-primary">
			<div class="panel-heading"><center>Today's stats</center></div>
			<div class="panel-body">
				<div id="chart-container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel panel-primary">
			<div class="panel-heading"><center>Yesterday's stats<center></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading"><center>Number of calls</div>
							<div class="panel-body">
								<center><?php echo $yesterday_numcalls; ?></center>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading"><center>Total call time</center></div>
							<div class="panel-body">
								<center><?php echo $yesterday_calltime; ?></center>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading"><center>Avg wait time</center></div>
							<div class="panel-body">
								<center><?php echo "s"; ?></center>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading"><center>Answered</center></div>
							<div class="panel-body">
								<center><?php echo $yesterday_answered; ?></center>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading"><center>Not answered</div>
							<div class="panel-body">
								<center><?php echo $yesterday_noanswer; ?></center>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading"><center>Failed</center></div>
							<div class="panel-body">
								<center><?php echo $yesterday_failed; ?></center>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	mysql_close();
	include('include/footer.inc.php');
?>