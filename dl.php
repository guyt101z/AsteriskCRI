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
	//if ($_SESSION['loggedin'] != true) {
	//	$authredirect = "Location: auth.php?action=login&redirect=".$_SERVER['PHP_SELF'];
    //	header($authredirect);
    //	die();
	//}
	
	include('include/dbconnect.inc.php');
	include('include/settings.inc.php');
	include('include/functions.inc.php');
	session_start();		
	
	$id = $_GET['id'];
	$result = mysql_fetch_array(mysql_query("SELECT * FROM cdr WHERE uniqueid LIKE '$id'", $db_asterisk));

	if ($_GET['type'] == "rec") {
		if ($allow_download_call == true) {
			$recordingfile = $result['recordingfile'];
			$audioyear = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(`calldate`,'%Y') FROM cdr WHERE recordingfile = '$recordingfile'", $db_asterisk));
			$audiomonth = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(`calldate`,'%m') FROM cdr WHERE recordingfile = '$recordingfile'", $db_asterisk));
			$audioday = mysql_fetch_array(mysql_query("SELECT DATE_FORMAT(`calldate`,'%d') FROM cdr WHERE recordingfile = '$recordingfile'", $db_asterisk));
			$audiosource = $asterisk_server."/arw/".$audioyear[0]."/".$audiomonth[0]."/".$audioday[0]."/".$recordingfile;

			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($audiosource).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($audiosource));
			readfile($audiosource);
			$redirect = "Location: call.php?id=".$id;
			header($redirect);
			exit;
		}
	}
	else if ($_GET['type'] == "cdr") {
		if ($allow_download_cdr == true) {
			header("Content-type: text/plain");
			header('Content-Type: application/octet-stream');
			if ($_GET['format'] == "txt") {
				$head = "Content-Disposition: attachment; filename=".$result['uniqueid'].".txt";
				header($head);
				print "calldate: ".$result['calldate']."\r\nclid: ".$result['clid']."\r\nsrc: ".$result['src']."\r\ndst: ".$result['dst']."\r\ndcontext: ".$result['dcontext']."\r\nchannel: ".$result['channel']."\r\ndstchannel: ".$result['dstchannel']."\r\nlastapp: ".$result['lastapp']."\r\nlastdata: ".$result['lastdata']."\r\nduration: ".$result['duration']."\r\nbillsec: ".$result['billsec']."\r\ndisposition: ".$result['disposition']."\r\namaflags: ".$result['amaflags']."\r\naccountcode: ".$result['accountcode']."\r\nuniqueid: ".$result['uniqueid']."\r\nuserfield: ".$result['userfield']."\r\ndid: ".$result['did']."\r\nrecordingfile: ".$result['recordingfile']."\r\ncnum: ".$result['cnum']."\r\ncnam: ".$result['cnam']."\r\noutbound_cnum: ".$result['outbound_cnum']."\r\noutbound_cnam: ".$result['outbounc_cnam']."\r\ndst_cnam: ".$result['dst_cnam'];
			}
			if ($_GET['format'] == "csv") {
				$head = "Content-Disposition: attachment; filename=".$result['uniqueid'].".csv";
				header($head);
				print "calldate,clid,src,dst,dcontext,channel,dstchannel,lastapp,lastdata,duration,billsec,disposition,amaflags,accountcode,uniqueid,userfield,did,recordingfile,cnum,cnam,outbound_cnum,outbound_cnam,dst_cnam\r\n\"".$result['calldate']."\",\"".$result['clid']."\",\"".$result['src']."\",\"".$result['dst']."\",\"".$result['dcontext']."\",\"".$result['channel']."\",\"".$result['dstchannel']."\",\"".$result['lastapp']."\",\"".$result['lastdata']."\",\"".$result['duration']."\",\"".$result['billsec']."\",\"".$result['disposition']."\",\"".$result['amaflags']."\",\"".$result['accountcode']."\",\"".$result['uniqueid']."\",\"".$result['userfield']."\",\"".$result['did']."\",\"".$result['recordingfile']."\",\"".$result['cnum']."\",\"".$result['cnam']."\",\"".$result['outbound_cnum']."\",\"".$result['outbound_cnam']."\",\"".$result['dst_cnam']."\"";
			}
		}
	}
?>