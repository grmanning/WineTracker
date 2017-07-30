<!DOCTYPE HTML>
<html lang="en-US">
<head>

<!-- 
A web app with client-side Javascript to record who partook of wine and who donated the wine at a Suspects' lunch.
 -->

<?php $version = "4.4"; ?>
<!--
Version 4.4: Sort the people's buttons based on imbibing
Version 4.3: Sort the candidates using sort()
Version 4.2: Realign buttons in a single table to save space
Version 4.1: Button placement changes
Version 4: New javascript prototype to allow dynamic calculation on the client side
Version 3: Capture both imbibers and donators. Added statistics of how many times people have donated versus imbibed.
Version 2: Replaced radio buttons with big text buttons
Version 1: First version

Program Purpose
	To provide a database of past events where one person buys a bottle of wine to be shared with others in order to help work out whose turn it is to buy
	the wine.
	
User Interaction Design
	Present the user with statistical information of past events, and provide the means to nominate who imbibed and who elected to pay.
	The interface is divided into four sections: the header, data entry, history and footer sections.
	The header idnetifies the app and contains a link to a help page.
	
	The data entry section has two columns of buttons, the left for recording who imbibed, and the right for recording who donated.
	The design of the buttons is to use the left button to identify the person, and the right button to be close enough to be associated with the same person but
	display the historical payment record of that person. This is shown as a percentage ratio of donations to imbibing with the count of each shown as well.
	The order of names is fixed, favouring the more frequent imbibers at the start of the list to reduce searching the list.
	The last row of buttons contains a Save and a Refresh button. Save will POST the changes back for processing, Refresh will POST for no action, causing a 
	reload of the page and clearing any selections.

	The history section has two columns. The left is a reverse chronilogical order display of the entire history of donations and imbibing. The right contains a button 
	to undo one event at a time, removing it form the history file. This action is logged in case something bad happens. Following the button are a series of 
	historical images of Doug the Giraffe.
	
Program Design
	The program is a combination of server side static HTML and PHP as well as client side CSS and JavaScript.
	The program structure is as follows:
		HTML Header
			CSS styles
		HTML Body
			Title and link to help page (static HTML)
			Input processing - respond to Save and Undo buttons, updating the history file (PHP)
			Output processing
				Table of data entry buttons and hidden text field for posting the results to the server. (Static HTML)
				Static declarations of variables and functions for Javascript
					refreshRatios() - a function to dynamically adjust the text and colours of the buttons to reflect any selection/deselections made
				Generated Javasript functions and code (PHP)
					Re-read the history file to get the latest data
					Populate a table of people's names and historical donation/imbibing data
					Generate functions for each person to handle mouse events on the buttons corresponding to each person.
				Main Javascript body (static Javascript)
					Call refreshRatios() to put the statistics in the right column buttons on load
		History division
			Generate the reverse history listing (PHP)
			Right column Undo button and Doug the Giraffe images (static HTML)
		Footer
			Version and build date/time (PHP)

 -->
<meta charset="UTF-8">
<title>Wine Tracker</title>

<!-- Style section -->

<style type="text/css">
/* Set all text to default Sans font */
   body {
		font-size: 3.5em;
		background-color: #00DDDD;
		font-family:Helvetica, Arial, Sans;
	}
/* Set header to fit nicely between images on a phone */
	.header {
		font-size: 1.2em;
	}
/* Set history text to monospaced to make the names readable	 */
   .history {
		font-size: 0.9em;
		background-color: #00DDDD;
		font-family: Monospace;
	}
/* Hide the textarea field used to send data back to the PHP server	 */
	.hide {
		display: none;
	}
/* Set the Save and Refresh buttons to look safe but different to the imbibers and donators	 */
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
/* Set the undo button to look like someting to be cautious of using */
	.undo {
		color: red;
		border-radius: 1em; 
		font-size:1.1em;
		background-color: #DDDDDD;
	}
/* Set the font size for the items in cells */
	td {
		font-size: 0.7em;
	}
/* Make imbibe and pay buttons the same size and style, but modify the colour and border to indicate actions and info  */
/* Default imbibe look - easy to read names */
	button.imbibe {
		padding-top: 0em;
		padding-bottom: 0.4em;
		font-size: 1.2em;
		border-radius:1em;
		border-width: thin;
		width: 8em;
		height: 1.2em;
		background-color: lightgrey;
		}
/* When selected, mark green		 */
	button.imbibeSelected {
		padding-top: 0em;
		padding-bottom: 0.4em;
		font-size: 1.2em;
		border-radius:1em;
		border-width: thin;
		width: 8em;
		height: 1.2em;
		background-color: lightgreen;	
		}
/* Defualt donator buttons are greyed out */
	button.pay {
		padding-top: 0em;
		padding-bottom: 0.4em;
		font-size: 1.2em;
		border-radius:1em;
		border-color: white;
		border-width: thin;
		width: 8em;
		height: 1.2em;
		background-color: lightgrey;
		color: grey;
		}
/* When an imbiber is selected on the left, highlight the donation history as a candidate donator */
    button.payImbiber {
        padding-top: 0em;
        padding-bottom: 0.4em;
        font-size: 1.2em;
        border-radius:1em;
		border-color: black;
		border-width: thin;
        width: 8em;
        height: 1.2em;
        background-color: lightgrey;
		color:black;
        }		
/* When selected as a donator, mark green         */
    button.paySelected {
        padding-top: 0em;
        padding-bottom: 0.4em;
        font-size: 1.2em;
        border-radius:1em;
		border-color: black;
		border-width: thin;
        width: 8em;
        height: 1.2em;
        background-color: lightgreen;
		color: black;
        }
/* Highlight the best candidate donator based on ratio of donations to imbibing */
    button.payHighlight {
        padding-top: 0em;
        padding-bottom: 0.4em;
        font-size: 1.2em;
        border-radius:1em;
		border-color: black;
		border-width: thin;
        width: 8em;
        height: 1.2em;
        background-color: yellow;
		color: black;
        }
/* Space out the buttons to give room for finger tap on a phone	 */
	.buttontable {
		border-spacing:0px 35px;
		}
/* Keep the title tight together */
	.headtable {
		border-spacing:0px 0px;
		}
</style>
</head>


<!-- Start of main body -->
<body>

<!-- Title section with link to help page -->
<table class="headtable" width="100%" border="0">
<tbody>
<tr bgcolor="#00DDDD" >
<td valign="top" align="left"><img src="betatrcorner.png" height="150px"></td>
<td valign="top" align="center" class="header">Suspects' Wine Tracker</td>
<td valign="top" align="right"><a href="winetrackerhelpBeta.php"><img src="glass-of-wine.jpg" height="100px"><br />HELP</a></td>
</tbody>
</table>

<!-- START OF INPUT PROCESSING SECTION -->

<!-- 
Process POST of form on server side in PHP.
If first load or Refresh, do nothing. If posting the Save button, extract and save the changes.
If posting the Undo button, read in the whole history and rewrite it all back without the last line.
 -->
<?php
// Set date to local time
	date_default_timezone_set('Australia/Canberra');
	
// 	If a Save requested, write the new entries to the history file, and make a record of the actions in the log file.
// The log file retains logs of actions including removal of entries in the history file so stuff-ups can be fixed.
    if ((isset($_POST['save'])) && (!empty($_POST["updateData"]))) {
        $dataline = htmlspecialchars($_POST["updateData"]);
		$actions = explode(";", $dataline);	
		$today = date("Y-m-d");
		$changecount = 0;
		foreach ($actions as $action) {
			if ($action != "") {
				$fh = fopen("winehistory.txt","a");
				fwrite($fh,"$action $today\n");
				$lh = fopen("winelog.txt","a");
				fwrite($lh,"ADD $action $name $today\n");
				fclose($lh);
				$changecount++;
			}			
    	}
//     	Display confirmation of the Save request and how many changes were saved. 
    	echo "<center><mark>$changecount changes saved successfully.</mark></center>";
	}
	
// 	If Undo is requested, replace the history files with all entries bar the last one.
	if (isset($_POST['undo'])) {
		$firsttime = 0;
// 		Check for an empty history file
    	$fh = fopen("winehistory.txt","r") 
    		or $firsttime = 1;
//     	If not empty, read it all into an array
		if (!$firsttime) {
			$i = 0;
			while(!feof($fh)) {
				$line[$i] = fgets($fh);
				$i = $i + 1;
				}
			fclose($fh);
// 			Reopen the history file to write over the top the entire contents less the last line
			$fh = fopen("winehistory.txt","w");
			$i = $i - 2;	 
			for ($t=0;$t < $i;$t++) {
				fwrite($fh,"$line[$t]");
				}
			fclose($fh);
// 			Record the what was deleted in the log file
			$lh = fopen("winelog.txt","a");
			fwrite($lh,"REM $line[$t]");
			fclose($lh);
// 			Display confirmation of one change removed
			echo "<center><mark>One change removed successfully.</mark></center>";
		}
	}
	?>
<!-- END OF INPUT PROCESSING SECTION -->

<!-- START OF OUTPUT PROCESSING SECTION -->

<!-- 
Display two columns of buttons. The left column is for those who inbibed, and shows the people's names.
The second column has statistics of past imbibing and donating corresponding to each person in the first column.
Clicking a name in the first column indicates the person imbibed. Clicking the button to the right of a name
indicates they are donating. The order of the people is fixed and based loosely on the frequency or imbibing and 
alphabetical order. Sorting by imbibing might be a future feature.
 -->

<?php
// read history into array
    $firsttime = 0;
    $fh = fopen("winehistory.txt","r") 
    	or $firsttime = 1;
    
    if ($firsttime) {
    	echo "No history";
    	 }
	else {
		// Read the history file and build the statistics of donations and imbibing
		$i = 0;
		$nameindx = 1; 	
		while(!feof($fh)) {
	 		$line[$i] = fgets($fh);
	 		$act = substr($line[$i],0,1);
	 		$name = substr(substr($line[$i],2),0,strpos(substr($line[$i ],2)," "));
	 		// build a table of names. Note the history file has been edited to include entries starting with "N" to ensure everyone is in this table
	 		// even if they never imbibe/donate. Two tables are built - $names to track each unique name and assign it an index, and $count to track the 
	 		// number of imbibings and donations per name. $name is indexed by the name of the person. $count is indexed by the number held in $names, and is an array of arrays,
	 		// where the second array is indexed by 0, 1 & 2. The 0th element holds the name of teh person, the 1th holds the count of donations ("P" for paid), and the 2th element the count of imbibings ("E" for enjoyed)
	 		if ($names[$name] == NULL) {
	 			$names[$name] = $nameindx;
	 			$indx = $nameindx;	 		
	 			$nameindx = $nameindx + 1;
	 		}
	 		else {
	 			$indx = $names[$name];
	 		}
	 		// count the number of times paid and imbibed for each name
	 		$count[$indx][0] = $name;
	 		if ($act == "P"){ 
	 			$count[$indx][1]++;
	 		}
	 		if ($act == "E") {
	 			$count[$indx][2]++;
	 		}
	 		$i = $i + 1;
	 	}
	 	
		// for each person, tidy up counts
	 	for ($row = 0; $row < count($count); $row++) {
	 		if ($count[$row][1] == NULL) {
	 			$count[$row][2] = 0;
	 		}
	 		if ($count[$row][2] == NULL) {
	 			$count[$row][2] = 0;
	 		}	 		
	 	}
	 }
	 fclose($fh);
	 
// 	 Now sort them by imbibings
	$count.sort(function(a,b) {return b[2]-a[2];});
	
	 
?>



<table class="buttontable" width="100%" border="0">
<tbody>

		<tr><td>Who imbibed today?</td><td>Who is donating?</td></tr>
		<tr><td align="center"><button class="imbibe" id="DougI" type="button" onMouseDown="return clickIDoug()">Doug</button></td><td align="center"><button class="pay" id="DougP" type="button" onMouseDown="return clickPDoug()">Doug</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="GeoffI" type="button" onMouseDown="return clickIGeoff()">Geoff</button></td><td align="center"><button class="pay" id="GeoffP" type="button" onMouseDown="return clickPGeoff()">Geoff</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="MartinI" type="button" onMouseDown="return clickIMartin()">Martin</button></td><td align="center"><button class="pay" id="MartinP" type="button" onMouseDown="return clickPMartin()">Martin</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="MaxineI" type="button" onMouseDown="return clickIMaxine()">Maxine</button></td><td align="center"><button class="pay" id="MaxineP" type="button" onMouseDown="return clickPMaxine()">Maxine</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="GeoffreyI" type="button" onMouseDown="return clickIGeoffrey()">Geoffrey</button></td><td align="center"><button class="pay" id="GeoffreyP" type="button" onMouseDown="return clickPGeoffrey()">Geoffrey</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="PamI" type="button" onMouseDown="return clickIPam()">Pam</button></td><td align="center"><button class="pay" id="PamP" type="button" onMouseDown="return clickPPam()">Pam</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="TrevorI" type="button" onMouseDown="return clickITrevor()">Trevor</button></td><td align="center"><button class="pay" id="TrevorP" type="button" onMouseDown="return clickPTrevor()">Trevor</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="AnneMarieI" type="button" onMouseDown="return clickIAnneMarie()">Anne-Marie</button></td><td align="center" align="center"><button class="pay" id="AnneMarieP" type="button" onMouseDown="return clickPAnneMarie()">Anne-Marie</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="CathI" type="button" onMouseDown="return clickICath()">Cath</button></td><td align="center"><button class="pay" id="CathP" type="button" onMouseDown="return clickPCath()">Cath</button></tr>
		<tr><td align="center"><button class="imbibe" id="NicoleI" type="button" onMouseDown="return clickINicole()">Nicole</button></td><td align="center"><button class="pay" id="NicoleP" type="button" onMouseDown="return clickPNicole()">Nicole</button></td></tr>
		<tr><td align="center"><button class="imbibe" id="PeterI" type="button" onMouseDown="return clickIPeter()">Peter</button></td><td align="center"><button class="pay" id="PeterP" type="button" onMouseDown="return clickPPeter()">Peter</button></td></tr>		

<!-- At the end of the table display a button to save the selection and a button to refresh the page. -->

<form action="winetrackerBeta.php" method="POST">
	<tr>
	<td valign="top" align="center" width="50%"><input type="submit" name="save" value="SAVE" class="save"></td>
	<td valign="top" align="center" width="50%"><input type="submit" name="refresh" value="Refresh" class="refresh"></td>
	</tr>
<!-- Create a hidden textarea field to store the selections for sbmission back to the PHP server. -->
<textarea id="updateData" name = "updateData" class="hide" cols="120"></textarea>
</tbody>
</table>

<!-- START OF CLIENT SIDE JAVASCRIPT -->

<!-- Static script to declare global variables and functions. -->

<script>
// Array and index of all people from the history file to be populated for use on the client side
// The people array contains objects of type person, containing attributes of:
// 	name: their name
// 	paid: count of times they donated in the history file
// 	enjoyed: count of times they imbibed in te history file
// 	imbibed: "Y" or "N" to indciate if the person has been selected as an imbiber this time
// 	paying: "Y" or "N" to indciate if the person has been selected as a donor this time
	
var people = [];  
var peopleindx = 0;

// refreshRatios iterates through the people array to recalculate the effect of the selections and display
// an updated view of the buttons by colouring them differently and by replacing the text in the right
// column buttons to show the recalculated statistics. This function is called every time a click changes 
// the selection of people.

function refreshRatios() {
// 	arrays to store the sorted people indexes and their calculated ratios to find the best candidate for donating today

	var i = 0;
	var candidates = [];
	var candindx  = 0;
	for (i = 0; i < peopleindx; i++) {
		var paid = people[i].paid;
		
// 		Add 1 if selected to pay today
		if (people[i].paying == "Y") { paid++; };
		var enjoyed = people[i].enjoyed;
		
// 		Add 1 if selected as an imbiber today
		if (people[i].imbibed == "Y") { enjoyed++ };
		
// 		Calculate the new ratio
		var ratio = 100 * (paid / enjoyed);
    	ratio = ratio.toFixed(0);
		if (isNaN(ratio)) { ratio = 0; };
		
// 		If an imbiber, add to the candidates list of donors for ranking
		if (people[i].imbibed == "Y") {
			var candidate = {};
			candidate.name = people[i].name;
			candidate.ratio = ratio;
			candidates[candindx++] = candidate;
			};

// 		Update the button text 
   		var rId = people[i].name.concat("P");
		var update = "".concat(ratio,"% (", paid, "/", enjoyed, ")");
    	document.getElementById(rId).innerHTML = update;
    }
    
//     Sort the candidates by ratio to have the lowest ratio placed first
    candidates.sort(function(a, b){return a.ratio - b.ratio});
    
//  Make a second pass now that the sorting is done to highlight the second column buttons according to who is selected for what and who is the best candidate.
//  Store the set of updates implied by the current selection in the hidden textarea field updateData	
	document.getElementById("updateData").value = "";
    for (i = 0; i < peopleindx; i++) {
    	var rId = people[i].name.concat("P");
    	if (people[i].paying == "Y") {
			document.getElementById(rId).className = "paySelected";			
			document.getElementById("updateData").value = document.getElementById("updateData").value.concat("P ",people[i].name, ";E ",people[i].name,";");
			}
		else {
			if (people[i].imbibed == "Y") {
				if (people[i].name == candidates[0].name) { 
// 					Give the first candidate with the lowest ratio a yellow highlight
					document.getElementById(rId).className = "payHighlight";
					document.getElementById("updateData").value = document.getElementById("updateData").value.concat("E ",people[i].name,";"); 
					}
				else { 
// 					All other imbibers are highlit with darker text and border
					document.getElementById(rId).className = "payImbiber";
					document.getElementById("updateData").value = document.getElementById("updateData").value.concat("E ",people[i].name,";"); 
					}
				}
			else {
				document.getElementById(rId).className = "pay";
				}
			}
		}
	return true;
}

// START OF GENERATED JAVASCRIPT

// This section rebuilds a client-side understanding of the current history file (possibly just updated) and then
// generates functions specific to each person to handle the button-click events.

// START OF GENERATED DECLARATIONS 

<?php
// read history into array
    $firsttime = 0;
    $fh = fopen("winehistory.txt","r") 
    	or $firsttime = 1;
    
    if ($firsttime) {
    	echo "No history";
    	 }
	else {
		// Read the history file and build the statistics of donations and imbibing
		$i = 0;
		$nameindx = 1; 	
		while(!feof($fh)) {
	 		$line[$i] = fgets($fh);
	 		$act = substr($line[$i],0,1);
	 		$name = substr(substr($line[$i],2),0,strpos(substr($line[$i ],2)," "));
	 		// build a table of names. Note the history file has been edited to include entries starting with "N" to ensure everyone is in this table
	 		// even if they never imbibe/donate.
	 		if ($names[$name] == NULL) {
	 			$names[$name] = $nameindx;
	 			$indx = $nameindx;	 		
	 			$nameindx = $nameindx + 1;
	 		}
	 		else {
	 			$indx = $names[$name];
	 		}
	 		// count the number of times paid and imbibed for each name
	 		$count[$indx][0] = $name;
	 		if ($act == "P"){ 
	 			$count[$indx][1]++;
	 		}
	 		if ($act == "E") {
	 			$count[$indx][2]++;
	 		}
	 		$i = $i + 1;
	 	}
	 	
		// for each person, tidy up counts and generate a function for clicking on each of their two buttons
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
		//  Store the person's history and current selection status in an array. 
	 		$whoratio = $who."ratio";	
	 		$whoI = $who."I"; 	
	 		$whoP = $who."P";
	 		$name = $who;	
			echo "
			var person = {name:\"$who\", paid:\"$paid\", enjoyed:\"$enjoyed\", imbibed:\"N\", paying:\"N\"};";
			echo "
			var $who = peopleindx;";
			echo "
			people[peopleindx++] = person;";

			// Define the functions to handle selection of each person to update who is imbibing.
			// When clicked, toggle between selected and unselected (stored in the people array) and changing the 
			// colour using a different class.			
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
			echo "refreshRatios();";
			echo "
			return true;";
			echo "
			}";
				
		// Define the functions to handle selection of each person to update who is donating/paying.
		// When clicked, toggle between selected and unselected (stored in the people array). The visual change
		// is done by calling refreshRatios().				
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
			echo "refreshRatios();";
			echo "
			return true;";
			echo "
			}";
			
	 	}
?>
// END OF GENERATED DECLARATIONS, START OF GENERATED JS CODE TO EXECUTE ON LOAD


// Calc & update ratios first time on load in JS
refreshRatios();
</script>

<!-- Generate HTML view of history file, an undo button and nice pictures of Doug the Giraffe alongside. -->

<!-- Left column - History of events in reverse chronological order -->

<?php
	 	echo "
	 	<hr><font color='blue'>History:</font><br />";	
	 	echo "
	 	<div class = \"history\">";	
	 	echo "
	 	<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tbody><td width=\"100%\">";
	 	$t = $i - 2;
// 	 	Set up to track blocks of events by date and make it easy to read a whole lunch event at a time
		$lastdate = "";
		$styleStart = "";
// 		Read through history array backwards to show the most recent events first
	 	while ($t >= 0) {
			$date = trim(substr(substr($line[$t],2),strpos(substr($line[$t],2)," ") + 1));
			$name = substr(substr($line[$t],2),0,strpos(substr($line[$t],2)," "));
			$act = substr($line[$t],0,1);
// 			If there is a change in date, swap the emphasis in the text to group each date's events 
			if ($date != $lastdate) {
				$lastdate = $date;
				if ($styleStart == "<strong>") {
					$styleStart = "";
					$styleEnd = "";
					}
				else {
					$styleStart = "<strong>";
					$styleEnd = "</strong>";
				}
			}
// 			If P action, call it donated (was Paid), if E call it Imbibed (was Enjoyed).
// 			Ignore others like "N" used to ensure all people are known event if they never imbibe/donate.
			if ($act == "P") {
				echo "
				$styleStart $date DONATED: $name $styleEnd<br />";
			}
			else {
				if ($act == "E") {
					echo "
				$styleStart $date IMBIBED: $name $styleEnd<br />";
				}
			}
			$t = $t - 1;
			}
	 	}
    ?>
    
<!--     Right column - Undo button to remove last entry/line in history file, followed by images of Doug the Giraffe -->
    
    </td>
    <td width="50%" valign="top" align="right">
    <input type="submit" name="undo" value="Undo one change" class="undo"><br><br>
    <img src="Doug_The_Giraffe1.jpg" width="400px"><br>
    <img src="Doug_The_Giraffe2.jpg" width="400px"><br>
    <img src="Doug_The_Giraffe3.jpg" width="400px"><br>
    </td></tbody></table>
    </div>
    
<!-- Close the form containing the three buttons -->
</form>

<!-- Generate the build date time for this version at the end -->

<?php
	echo "<font size=\"6\">Version $version Build ";
	date_default_timezone_set("Australia/Canberra");
	echo date("F d Y H:i:s", filectime("winetrackerBeta.php"));
	echo "</font>";
?>
</body>
</html>