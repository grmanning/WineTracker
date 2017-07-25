<!DOCTYPE HTML>
<html lang="en-US">
<head>
<!-- Version 4.1 
Center justify buttons
Move Undo to History
 -->
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
   .history {
		font-size: 0.6em;
		background-color: #00DDDD;
	}
	.hide {
		display: none;
	}
	.save {
		color: green;
		background-color: #DDDDDD;
		border-radius: 1em; 
		font-size:1.2em;
		width: 8em;
	}
	.refresh {
		color: green;
		border-radius: 1em; 
		font-size:1.2em;
		background-color: #DDDDDD;
		width: 8em;
	}
	.undo {
		color: red;
		border-radius: 1em; 
		font-size:1.5em;
		background-color: #DDDDDD;
	}
	img.resize {
		max-width:50%;
		max-height:50%;
	}
	td {
		font-size: 0.7em;
	}
	button.imbibe {
		padding-top: 0em;
		padding-bottom: 0.4em;
		font-size: 2em;
		border-radius:1em;
		width: 6.5em;
		height: 1.2em;
		background-color: lightgrey;
		
		}
	button.imbibeSelected {
		padding-top: 0em;
		padding-bottom: 0.4em;
		font-size: 2em;
		border-radius:1em;
		width: 6.5em;
		height: 1.2em;
		background-color: lightgreen;	
		}
	
	table {
		border-spacing: 5px 30px;
		}
	table.3col {
		border-spacing: 25px 0px;
		}
	button.pay {
		padding-top: 0em;
		padding-bottom: 0.4em;
		font-size: 2em;
		border-radius:1em;
		border-color: white;
		border-width: medium;
		width: 7em;
		height: 1.2em;
		background-color: lightgrey;
		color: grey;
		}
    button.paySelected {
        padding-top: 0em;
        padding-bottom: 0.4em;
        font-size: 2em;
        border-radius:1em;
		border-color: black;
		border-width: thick;
        width: 8em;
        height: 1.2em;
        background-color: lightgreen;
		color: black;
        }
    button.payImbiber {
        padding-top: 0em;
        padding-bottom: 0.4em;
        font-size: 2em;
        border-radius:1em;
		border-color: black;
		border-width: thick;
        width: 8em;
        height: 1.2em;
        background-color: lightgrey;
		color:black;
        }
    button.payHighlight {
        padding-top: 0em;
        padding-bottom: 0.4em;
        font-size: 2em;
        border-radius:1em;
		border-color: black;
		border-width: thick;
        width: 8em;
        height: 1.2em;
        background-color: yellow;
		color: black;
        }
</style>

</head>
<body>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr bgcolor="#00DDDD" >
<td valign="top" align="center">Suspects'<br>Wine<br>Tracker</td>
<td valign="top" align="centre"><img src="glass-of-wine.jpg" width="200px"></td>
</tbody>
</table>




<?php
date_default_timezone_set('Australia/Canberra');
    if ((isset($_POST['save'])) && (!empty($_POST["updateData"]))) {
        $dataline = htmlspecialchars($_POST["updateData"]);
		$actions = explode(";", $dataline);	
		$today = date("Y-m-d");
		foreach ($actions as $action) {
			if ($action != "") {
				$fh = fopen("winehistory.txt","a");
				fwrite($fh,"$action $today\n");
				$lh = fopen("winelog.txt","a");
				fwrite($lh,"ADD $action $name $today\n");
				fclose($lh);
			}			
    	}
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








<script>
	var people = [];
	var peopleindx = 0;
function refreshRatios() {
	var highlight = [];
	var ratios = [];
	var i = 0;
	for (i = 0; i < peopleindx; i++) {
		var paid = people[i].paid;
		if (people[i].paying == "Y") { paid++; };
		var enjoyed = people[i].enjoyed;
		if (people[i].imbibed == "Y") { enjoyed++ };
    	var ratio = 100 * (paid / enjoyed);
    	ratio = ratio.toFixed(0);
    	if (people[i].imbibed == "Y") {
    		if (highlight.length == 0) { 
    			highlight[0] = i;
    			ratios[0] = ratio;
    			}
			else {
				var Hlen = highlight.length;
				for (j = 0; j < Hlen; j++) {
					if (ratios[j] > ratio) {
						for (k = j; k < Hlen; k++) {
							highlight[k + 1] = highlight[k];
							ratios[k + 1] = ratios[k];
						}
						highlight[j] = i;
						ratios[j] = ratio;
					}
				}
    		}
    	}
   		var rId = people[i].name.concat("P");
   		var name = people[i].name;
   		if (name == "AnneMarie") { name = "Anne-Marie"; };
   		if (isNaN(ratio)) { ratio = 0; };
		var update = "".concat(ratio,"% (", paid, "/", enjoyed, ")");
    	document.getElementById(rId).innerHTML = update;
    }	document.getElementById("updateData").value = "";
    for (i = 0; i < peopleindx; i++) {
    	var rId = people[i].name.concat("P");
    	if (people[i].paying == "Y") {
			document.getElementById(rId).className = "paySelected";			
			document.getElementById("updateData").value = document.getElementById("updateData").value.concat("P ",people[i].name, ";E ",people[i].name,";");
			}
		else {
			if (highlight.length > 0) {
				if (highlight[0] == i) { 
					document.getElementById(rId).className = "payHighlight";
					document.getElementById("updateData").value = document.getElementById("updateData").value.concat("E ",people[i].name,";"); 
					}
				else { 
					if (people[i].imbibed == "Y") {
						document.getElementById(rId).className = "payImbiber";
						document.getElementById("updateData").value = document.getElementById("updateData").value.concat("E ",people[i].name,";"); 
						}
					else {
						document.getElementById(rId).className = "pay";
					}
				}
			}
			else {
				document.getElementById(rId).className = "pay";
			}
		}
    }
	return true;
}
</script>



<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr bgcolor="#FFFF11" >
<td 1 class="pageName" id="form" nowrap="nowrap" valign="top" bgcolor="#00DDDD" width="50%" colspan="1">
<fieldset>
	<legend><font size="6.5em">Who imbibed today?</font></legend>

	<table width="100%" border="0" cellspacing="12" cellpadding="0">	
		<tr><td align="center"><button class="imbibe" id="AnneMarieI" type="button" onMouseDown="return clickIAnneMarie()">Anne-Marie</button></td></tr>
		<td align="center"><button class="imbibe" id="CathI" type="button" onMouseDown="return clickICath()">Cath</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="DougI" type="button" onMouseDown="return clickIDoug()">Doug</button></td></tr>
		<td align="center"><button class="imbibe" id="GeoffI" type="button" onMouseDown="return clickIGeoff()">Geoff</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="GeoffreyI" type="button" onMouseDown="return clickIGeoffrey()">Geoffrey</button></td></tr>
		<td align="center"><button class="imbibe" id="MartinI" type="button" onMouseDown="return clickIMartin()">Martin</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="MaxineI" type="button" onMouseDown="return clickIMaxine()">Maxine</button></td></tr>
		<td align="center"><button class="imbibe" id="NicoleI" type="button" onMouseDown="return clickINicole()">Nicole</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="PamI" type="button" onMouseDown="return clickIPam()">Pam</button></td></tr>
		<td align="center"><button class="imbibe" id="PeterI" type="button" onMouseDown="return clickIPeter()">Peter</button></td></tr>		
		<tr><td align="center"><button class="imbibe" id="TrevorI" type="button" onMouseDown="return clickITrevor()">Trevor</button></td></tr>
	</table>

</td>
<td 1 class="pageName" id="form" nowrap="nowrap" valign="top" bgcolor="#00DDDD" width="50%" colspan="1">
<fieldset>
	<legend><font size="6.5em">Who is paying?</font></legend>

	<table width="100%" border="0" cellspacing="12" cellpadding="0">	
		<tr><td align="center" align="center"><button class="pay" id="AnneMarieP" type="button" onMouseDown="return clickPAnneMarie()">Anne-Marie</button></td></tr>
		<td align="center"><button class="pay" id="CathP" type="button" onMouseDown="return clickPCath()">Cath</button></td></tr>
		<tr><td align="center"><button class="pay" id="DougP" type="button" onMouseDown="return clickPDoug()">Doug</button></td></tr>
		<td align="center"><button class="pay" id="GeoffP" type="button" onMouseDown="return clickPGeoff()">Geoff</button></td></tr>
		<tr><td align="center"><button class="pay" id="GeoffreyP" type="button" onMouseDown="return clickPGeoffrey()">Geoffrey</button></td></tr>
		<td align="center"><button class="pay" id="MartinP" type="button" onMouseDown="return clickPMartin()">Martin</button></td></tr>
		<tr><td align="center"><button class="pay" id="MaxineP" type="button" onMouseDown="return clickPMaxine()">Maxine</button></td></tr>
		<td align="center"><button class="pay" id="NicoleP" type="button" onMouseDown="return clickPNicole()">Nicole</button></td></tr>
		<tr><td align="center"><button class="pay" id="PamP" type="button" onMouseDown="return clickPPam()">Pam</button></td></tr>
		<td align="center"><button class="pay" id="PeterP" type="button" onMouseDown="return clickPPeter()">Peter</button></td></tr>		
		<tr><td align="center"><button class="pay" id="TrevorP" type="button" onMouseDown="return clickPTrevor()">Trevor</button></td></tr>
	</table>
</td>
</td>
</tr>
</tbody>
</table>


<form action="winetracker.php" method="POST">
<fieldset>
<!-- 	<legend><font size="6.5em">History:</font></legend> -->
	<table class="3col" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
	<td valign="top" align="center" width="50%"><input type="submit" name="save" value="SAVE" class="save"></td>
	<td valign="top" align="center" width="50%"><input type="submit" name="refresh" value="Refresh" class="refresh"></td>
	</tr>
<textarea id="updateData" name = "updateData" class="hide" cols="120"></textarea>
</fieldset>
</form>

</tbody>
</table>



<?php
    $firsttime = 0;
    $fh = fopen("winehistory.txt","r") 
    	or $firsttime = 1;
    
    if ($firsttime) {
    	echo "No history";
    	 }
	else {
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
//	 	echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tbody>";
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
//  Make HTML ratios updatable
	 		$whoratio = $who."ratio";	
	 		$whoI = $who."I"; 	
	 		$whoP = $who."P";
	 		$name = $who;
	 		if ($name == "AnneMarie") {$name = "Anne-Marie";};		
	 		echo "
	 		<script>";
			echo "
			var person = {name:\"$who\", paid:\"$paid\", enjoyed:\"$enjoyed\", imbibed:\"N\", paying:\"N\"};";
			echo "
			var $who = peopleindx;";
			echo "
			people[peopleindx++] = person;";
	 		echo "
	 		var ratio = 100 * (people[$who].paid / people[$who].enjoyed);";
	 		echo "
	 		ratio = ratio.toFixed(0);";
	 		echo "var update = \"\".concat(\"$name\", \" - \", ratio, \"% \", people[$who].paid, \"/\", people[$who].enjoyed);";
    		echo "
    		document.getElementById(\"$whoP\").innerHTML = update;";

// Define the clickI functions for each person to update who is imbibing			
			echo "
			function clickI$who() {";
			echo "
				if (people[$who].imbibed == \"N\") {";
			echo "
					document.getElementById(\"$whoI\").className = \"imbibeSelected\";";
			echo "
					people[$who].imbibed = \"Y\";";
			echo "
				}";
			echo "
				else {";
			echo "
					document.getElementById(\"$whoI\").className = \"imbibe\";";

			echo "
					people[$who].imbibed = \"N\";";
			echo "
				}";
			echo "return refreshRatios();";
			echo "
			return true;";
			echo "
			}";
				
// Define the ClickP functions for each person to update who is paying			
			echo "
			function clickP$who() {";
			echo "
				if (people[$who].paying == \"N\") {";
			echo "
					people[$who].paying = \"Y\";";
			echo "
				}";
			echo "
				else {";
			echo "
					people[$who].paying = \"N\";";
			echo "
				}";
			echo "return refreshRatios();";
			echo "
			return true;";
			echo "
			}";
			
			
			
			echo "
			</script>";
	 	}

// Calc & update ratios first time on load in JS
	 	echo "<script>";
	 	echo "refreshRatios();";
		echo "</script>";	
		echo "<div class = \"history\">";	
	 	echo "<hr><font color='blue'>History:</font><br />";	
	 	echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tbody><td width=\"50%\">";
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
    </td>
    <td width="50%" valign="top" align="right">
    <input type="submit" name="undo" value="Undo last action in history" class="undo"><br><br>
    <img src="Doug_The_Giraffe1.jpg" width="400px"><br>
    <img src="Doug_The_Giraffe2.jpg" width="400px"><br>
    <img src="Doug_The_Giraffe3.jpg" width="400px"><br>
    </td></tbody></table>
    </div>

</fieldset>
</form>

<font size="6">Version 4 Build 
<?php
	date_default_timezone_set("Australia/Canberra");
	echo date("F d Y H:i:s", filectime("winetracker.php"));
	echo "</font>";
?>
</body>
</html>