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
	//this code isnt working
	//if ($_SESSION['loggedin'] != true) {
	//	$authredirect = "Location: auth.php?action=login&redirect=".$_SERVER['PHP_SELF'];
    //	header($authredirect);
    //	die();
	//}
	
	include('include/dbconnect.inc.php');
	include('include/settings.inc.php');
	include('include/functions.inc.php');
	session_start();		
?>

<html>
	<head>
		<title>Call Information</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="jplayer/skin/black-yellow/css/style.css" rel="stylesheet" />
		<link href="jplayer/skin/black-yellow/css/themicons.css" rel="stylesheet" />
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="jplayer/jplayer/jquery.jplayer.min.js"></script>
	</head>
	<body>
		<?php
			$id = $_GET['id'];
			//get the call data from db and cut up the recordingfile value to make up the url for fetching the file off the asterisk server
			$result = mysql_fetch_array(mysql_query("SELECT * FROM cdr WHERE uniqueid LIKE '$id'", $db_asterisk));
			$recordingfile = $result['recordingfile'];
			$audioyear = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(`calldate`,'%Y') FROM cdr WHERE recordingfile = '$recordingfile'", $db_asterisk));
			$audiomonth = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(`calldate`,'%m') FROM cdr WHERE recordingfile = '$recordingfile'", $db_asterisk));
			$audioday = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(`calldate`,'%d') FROM cdr WHERE recordingfile = '$recordingfile'", $db_asterisk));
			$audiosource = $asterisk_server."/arw/".$audioyear[0]."/".$audiomonth[0]."/".$audioday[0]."/".$recordingfile;

			//convert the duration into a human readable format
			if ($row['duration'] >= "3600") {
				$dur = gmdate("H:i:s", $row['duration']);
			}
			else {
				$dur = gmdate("i:s", $row['duration']);
			}
		?>
		<!--js for jplayer-->
		<script type="text/javascript">
		//<![CDATA[
		$(document).ready(function(){

			$("#jquery_jplayer_1").jPlayer({
				ready: function (event) {
					$(this).jPlayer("setMedia", {
						title: "Call Recording",
						wav: "<?php echo $audiosource; ?>",
					});
				},
				swfPath: "jplayer/jplayer",
				supplied: "wav",
				wmode: "window",
				useStateClassSkin: true,
				autoBlur: false,
				smoothPlayBar: true,
				keyEnabled: true,
				remainingDuration: true,
				toggleDuration: true
			});
		});
		//]]>
		</script>
		<div style="height: 20px"></div>
		<div class="container">		
			<div class="panel-group" id="accordion">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#audioplayer" class="collapsed">
								Audio player
							</a>
						</h4>
					</div>
					<div id="audioplayer" class="panel-collapse collapse">
						<div class="panel-body">
							<div id="jquery_jplayer_1" class="jp-jplayer" style="width: 0px; height: 0px;"><img id="jp_poster_0" style="width: 0px; height: 0px; display: none;"><audio id="jp_audio_0" preload="metadata" src="<?php echo $audiosource; ?>"></audio></div>
							<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
								<div class="jp-interface">
									<div class="jp-button jp-playpaus-button">
										<button class="jp-play" role="button" tabindex="0">play</button>
									</div>
									<div class="jp-time-rail">
										<div class="jp-progress">
											<div class="jp-seek-bar" style="width: 100%;">
												<div class="jp-play-bar" style="width: 0%;"></div>
											</div>
										</div>
									</div>
									<div class="jp-button jp-volume-button">
										<button class="jp-mute" role="button" tabindex="0">max volume</button>
									</div>
									<div class="jp-volume-bar">
										<div class="jp-volume-bar-value" style="width: 80%;"></div>
									</div>
								</div>
								<div class="jp-no-solution" style="display: none;">
									<span>Update Required</span>
									Your browser is too old to play this media - please update to recent version.
								</div>
							</div><!-- .jp-audio -->
						</div>
					</div>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#calldetails" class="collapsed">
								Call details
							</a>
						</h4>
					</div>
					<div id="calldetails" class="panel-collapse collapse">
						<table class="table">
							<tbody>
								<tr>
									<th>Date/Time</th>
									<td><?php echo $result['calldate']; ?></td>
								</tr>
								<tr>
									<th>Source</th>
									<td><?php echo $result['clid']; ?></td>
								</tr>
								<tr>
									<th>Destination</th>
									<td><?php echo $result['dst']; ?></td>
								</tr>
								<tr>
									<th>Duration</th>
									<td><?php echo $dur; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#asteriskinfo" class="collapsed">
								Asterisk CDRDB Info
							</a>
						</h4>
					</div>
					<div id="asteriskinfo" class="panel-collapse collapse">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<th>Field</th>
								<th>Value</th>
							</thead>
							<tbody>
								<tr><td>calldate</td><td><?php echo $result['calldate']; ?></td></tr>
								<tr><td>clid</td><td><?php echo $result['clid']; ?></td></tr>
								<tr><td>src</td><td><?php echo $result['src']; ?></td></tr>
								<tr><td>dst</td><td><?php echo $result['dst']; ?></td></tr>
								<tr><td>dcontext</td><td><?php echo $result['dcontext']; ?></td></tr>
								<tr><td>channel</td><td><?php echo $result['channel']; ?></td></tr>
								<tr><td>dstchannel</td><td><?php echo $result['dstchannel']; ?></td></tr>
								<tr><td>lastapp</td><td><?php echo $result['lastapp']; ?></td></tr>
								<tr><td>lastdata</td><td><?php echo $result['lastdata']; ?></td></tr>
								<tr><td>duration</td><td><?php echo $result['duration']; ?></td></tr>
								<tr><td>billsec</td><td><?php echo $result['billsec']; ?></td></tr>
								<tr><td>disposition</td><td><?php echo $result['disposition']; ?></td></tr>
								<tr><td>amaflags</td><td><?php echo $result['amaflags']; ?></td></tr>
								<tr><td>accountcode</td><td><?php echo $result['accountcode']; ?></td></tr>
								<tr><td>uniqueid</td><td><?php echo $result['uniqueid']; ?></td></tr>
								<tr><td>userfield</td><td><?php echo $result['userfield']; ?></td></tr>
								<tr><td>did</td><td><?php echo $result['did']; ?></td></tr>
								<tr><td>recordingfile</td><td><?php echo $result['recordingfile']; ?></td></tr>
								<tr><td>cnum</td><td><?php echo $result['cnum']; ?></td></tr>
								<tr><td>cnam</td><td><?php echo $result['cnam']; ?></td></tr>
								<tr><td>outbound_cnum</td><td><?php echo $result['outbound_cnum']; ?></td></tr>
								<tr><td>outbound_cnam</td><td><?php echo $result['outbound_cnam']; ?></td></tr>
								<tr><td>dst_cnam</td><td><?php echo $result['dst_cnam']; ?></td></tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#download" class="collapsed">
								Save/Download
							</a>
						</h4>
					</div>
					<div id="download" class="panel-collapse collapse">
						<div class="panel-body">
							<?php
								if ($allow_download_call == true) { echo "<a href=\"dl.php?type=rec&id=".$id."\"><i class=\"fa fa-cloud-download fa-1x\"></i> Download audio file</a><br>"; }
								if ($allow_download_cdr == true) { echo "<a href=\"dl.php?type=cdr&format=txt&id=".$id."\"><i class=\"fa fa-cloud-download fa-1x\"></i> Download CDR record (txt)</a><br><a href=\"dl.php?type=cdr&format=csv&id=".$id."\"><i class=\"fa fa-cloud-download fa-1x\"></i> Download CDR record (txt)</a><br>"; }
							?>
							<a href="cdrflag.php?id=<?php echo $id; ?>"><i class="fa fa-flag fa-1x"></i> Flag this call</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>