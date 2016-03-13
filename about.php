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
	$buffer=str_replace("%TITLE%","About",$buffer);
	echo $buffer;
?>

<div class="container">
	<div class="page-header">
		<h1>About CRI</h1>
	</div>
	<p>AsteriskCRI is available under the GNU General Public License v3.</p>
	<br><br>
	<div class="panel panel-primary">
		<div class="panel-heading">
			Authors
		</div>
		<div class="panel-body">
			<p><strong>Fraser Jamieson</strong> (<a href="mailto:fj@fraser.re">fj@fraser.re</a>)</p>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			Acknowledgements
		</div>
		<div class="panel-body">
			<p>CRI uses the following componenet under license:</p>
			<ul>
				<li><strong>Bootstrap</strong> - &copy; 2015 Twitter. Used under MIT license.</li>
				<li><strong>jQuery</strong> - &copy; 2005, 2015 jQuery Foundation, Inc. Used under MIT license.</li>
				<li><strong>jPlayer</strong> - &copy; 2009 - 2016 Happyworm Ltd. Used under MIT license.</li>
			</ul>
		</div>
	</div>
	<div class="panel panel-primary">
		<div class="panel-heading">
			Version
		</div>
		<div class="panel-body">
			<p>Current version: <?php echo $release; ?></p>
		</div>
	</div>
</div>
		

<?php include ('include/footer.inc.php'); ?>