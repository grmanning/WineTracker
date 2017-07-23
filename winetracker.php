<!DOCTYPE HTML>
<html lang="en-US">
<head>
<!-- Version 2 -->
<meta charset="UTF-8">
<title>Wine Tracker</title>

<style type="text/css">
   body {
		font-size: 3.5em;
		background-color: #00DDDD;
	}
	input[type=radio] {
		border: 0px;
		width: 10%;
		height: 4em;
	}
	input[type=submit] {
		padding:5px 15px; 
		border:0 none;
		cursor:pointer;
		-webkit-border-radius: 10px;
		border-radius: 10px; 
		font-size:0.5em;
		width: 6em
	}
   .history {
		font-size: 0.6em;
		background-color: #00DDDD;
	}
	.refresh {
		color: green;
		background-color: #DDDDDD;
	}
	.undo {
		color: red;
		background-color: #DDDDDD;
	}
    </style>

</head>
<body>
<?php
	$name = "";
	if (isset($_POST['anne-marie'])) {
		$name = "Anne-Marie"; $action = "P";
	}
	if (isset($_POST['cath'])) {
		$name = "Cath"; $action = "P";
	}
	if (isset($_POST['doug'])) {
		$name = "Doug"; $action = "P";
	}
	if (isset($_POST['geoff'])) {
		$name = "Geoff"; $action = "P";
	}
	if (isset($_POST['geoffrey'])) {
		$name = "Geoffrey"; $action = "P";
	}
	if (isset($_POST['martin'])) {
		$name = "Martin"; $action = "P";
	}
	if (isset($_POST['maxine'])) {
		$name = "Maxine"; $action = "P";
	}
	if (isset($_POST['nicole'])) {
		$name = "Nicole"; $action = "P";
	}
	if (isset($_POST['pam'])) {
		$name = "Pam"; $action = "P";
	}
	if (isset($_POST['peter'])) {
		$name = "Peter"; $action = "P";
	}
	if (isset($_POST['trevor'])) {
		$name = "Trevor"; $action = "P";
	}
	if (isset($_POST['anne-marieD'])) {
		$name = "Anne-Marie"; $action = "E";
	}
	if (isset($_POST['cathD'])) {
		$name = "Cath"; $action = "E";
	}
	if (isset($_POST['dougD'])) {
		$name = "Doug"; $action = "E";
	}
	if (isset($_POST['geoffD'])) {
		$name = "Geoff"; $action = "E";
	}
	if (isset($_POST['geoffreyD'])) {
		$name = "Geoffrey"; $action = "E";
	}
	if (isset($_POST['martinD'])) {
		$name = "Martin"; $action = "E";
	}
	if (isset($_POST['maxineD'])) {
		$name = "Maxine"; $action = "E";
	}
	if (isset($_POST['nicoleD'])) {
		$name = "Nicole"; $action = "E";
	}
	if (isset($_POST['pamD'])) {
		$name = "Pam"; $action = "E";
	}
	if (isset($_POST['peterD'])) {
		$name = "Peter"; $action = "E";
	}
	if (isset($_POST['trevorD'])) {
		$name = "Trevor"; $action = "E";
	}
	if ($name != "") {
		$today = date("Y-m-d");
   	 	$fh = fopen("winehistory.txt","a");
    	fwrite($fh,"$action $name $today\n");
    	$lh = fopen("winelog.txt","a");
		fwrite($lh,"ADD $action $name $today\n");
		fclose($lh);
	 }
	if (isset($_POST['undo'])) {
		$firsttime = 0;
    	$fh = fopen("winehistory.txt","r") 
    		or $firsttime = 1;
    
		if (!$firsttime) {
			$i = 0;
			while(!feof($fh)) {
				$line[$i] = fgets($fh);
				$i = $i + 1;
				}
			fclose($fh);
			$fh = fopen("winehistory.txt","w");
			$i = $i - 2;	 
			for ($t=0;$t < $i;$t++) {
				fwrite($fh,"$line[$t]");
				}
			fclose($fh);
			$lh = fopen("winelog.txt","a");
			fwrite($lh,"REM $line[$t]");
			fclose($lh);
		}
	}
    ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr bgcolor="#FFFF11">
<td 1 class="pageName" id="form" nowrap="wrap" align="middle" valign="middle" bgcolor="#00DDDD" width="50%">
	Suspects' <br>Wine <br>Tracker<br><font size="4">Version 3 Build 
	<?php 
		date_default_timezone_set("Australia/Canberra");
		echo date("F d Y H:i:s.", filectime("winetracker.php"));?></font>
	</td>
<td 2 class="pageName" id="logo" nowrap="nowrap" align="middle" valign="middle" bgcolor="#00DDDD" width="50%">
<?php
$randomnum = rand(1,3);
echo "<p align=\"middle\"><img width=\"400\" height=\"320\" alt=\"\" border=\"0\" src=\"Doug_The_Giraffe$randomnum.jpg\" /></p>";
?>
</td>
</tr>
<tr bgcolor="#FFFF11" >
<td 1 class="pageName" id="form" nowrap="nowrap" valign="top" bgcolor="#00DDDD" width="50%" colspan="1">
<form action="winetracker.php" method="POST">
<fieldset>
	<legend><font size="6.5em">Who paid today?</font></legend>
	<table width="100%" border="0" cellspacing="12" cellpadding="0"><tr  width="50%">	
		<td><input type="submit" name="anne-marie" value="Anne-Marie"></td>
		<td><input type="submit" name="cath" value="Cath"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="doug" value="Doug"></td>
		<td><input type="submit" name="geoff" value="Geoff"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="geoffrey" value="Geoffrey"></td>
		<td><input type="submit" name="martin" value="Martin"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="maxine" value="Maxine"></td>
		<td><input type="submit" name="nicole" value="Nicole"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="pam" value="Pam"></td>
		<td><input type="submit" name="peter" value="Peter"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="trevor" value="Trevor"></td>
		<td></td>
	</tr>
	</table>
</td>
<td 1 class="pageName" id="form" nowrap="nowrap" valign="top" bgcolor="#00DDDD" width="50%" colspan="1">
<form action="winetracker.php" method="POST">
<fieldset>
	<legend><font size="6.5em">Who enjoyed as well?</font></legend>
	<table width="100%" border="0" cellspacing="12" cellpadding="0"><tr  width="50%">	
		<td><input type="submit" name="anne-marieD" value="Anne-Marie"></td>
		<td><input type="submit" name="cathD" value="Cath"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="dougD" value="Doug"></td>
		<td><input type="submit" name="geoffD" value="Geoff"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="geoffreyD" value="Geoffrey"></td>
		<td><input type="submit" name="martinD" value="Martin"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="maxineD" value="Maxine"></td>
		<td><input type="submit" name="nicoleD" value="Nicole"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="pamD" value="Pam"></td>
		<td><input type="submit" name="peterD" value="Peter"></td>
	</tr>
	<tr width="50%">
		<td><input type="submit" name="trevorD" value="Trevor"></td>
		<td></td>
	</tr>
	</table>
</td>
</tr>
</tbody>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td 4 valign="top" width="50%">
<form action="winetracker.php" method="POST">
<fieldset>
	<legend><font size="6.5em">History:</font></legend>
<input type="submit" name="refresh" value="Refresh" class="refresh">
<br><br>
<input type="submit" name="undo" value="Undo" class="undo">
</fieldset>
</form>
</td>
<td 3>
    <div class = "history">
<?php
    $firsttime = 0;
    $fh = fopen("winehistory.txt","r") 
    	or $firsttime = 1;
    
    if ($firsttime) {
    	echo "No history";
    	 }
	else {
		echo "<font color='blue'>Totals to date (Paid/Enjoyed):</font><br />";
		$i = 0;
		$nameindx = 1; 	
		while(!feof($fh)) {
	 		$line[$i] = fgets($fh);
	 		$act = substr($line[$i],0,1);
	 		$name = substr(substr($line[$i],2),0,strpos(substr($line[$i ],2)," "));
	 		if ($names[$name] == NULL) {
	 			$names[$name] = $nameindx;
	 			$indx = $nameindx;	 		
	 			$nameindx = $nameindx + 1;
	 		}
	 		else {
	 			$indx = $names[$name];
	 		}
	 		$count[$indx][0] = $name;
	 		if ($act == "P"){ 
	 			$count[$indx][1]++;
	 		}
	 		if ($act == "E") {
	 			$count[$indx][2]++;
	 		}
	 		$i = $i + 1;
	 		}
	 	echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tbody>";
	 	for ($row = 1; $row < count($count); $row++) {
	 		$who = $count[$row][0];
	 		$paid = $count[$row][1];
	 		if ($paid == NULL) {
	 			$paid = 0;
	 		}
	 		$enjoyed = $count[$row][2];
	 		if ($enjoyed == NULL) {
	 			$enjoyed = 0;
	 		}	 		
	 		echo "<tr><td>$who:</td><td>$paid/$enjoyed</td></tr>";
	 	}
	 	echo "</tbody></table>";
	 echo "<hr><font color='blue'>History:</font><br />";	
	 $t = $i - 2;
	 while ($t >= 0) {
	 	$date = substr(substr($line[$t],2),strpos(substr($line[$t],2)," ") + 1);
	 	$name = substr(substr($line[$t],2),0,strpos(substr($line[$t],2)," "));
	 	$act = substr($line[$t],0,1);
	 	if ($act == "P") {
	 		echo "$date PAID $name<br />";
	 	}
	 	else {
	 		echo "$date ENJOYED $name<br />";
	 	}
	 	$t = $t - 1;
	 	}
	 }
    ?>
    </div>
</td>

</tr>
</tbody>
</table>

</body>
</html>