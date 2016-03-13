<?php
	$db_asterisk = mysql_connect($asterisk_db_server,$asterisk_db_user, $asterisk_db_pass); 
	$db_cri = mysql_connect($cri_db_server, $cri_db_viewer, $cri_db_pass, true); 

	mysql_select_db('asteriskcdrdb', $db_asterisk);
	mysql_select_db('cri', $db_cri);
?>