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
	$buffer=str_replace("%TITLE%","Access Denied",$buffer);
	echo $buffer;
?>

<div class="container">
	<div class="page-header">
		<h1>Access Denied - <?php echo $denied_resource; ?></h1>
	</div>
	<p>You do not have the appropriate permissions to access this resource. This incident will be reported.</p>
	<p>If you think this is incorrect, please contact your line manager for assistance, or submit a support ticket.</p>
	<br><br><br><br><br>
</div>

<?php include('include/footer.inc.php'); ?>