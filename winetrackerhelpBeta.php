<!DOCTYPE HTML>
<html lang="en-US">
<head>
<!-- 
Help for the Beta version of Wine Tracker

This help file is to describe how to use the Beta version of the Wint Tracker web app. It offers button to navigate to that app.

 -->
<meta charset="UTF-8">
<title>Wine Tracker Help</title>

<style type="text/css">
   body {
		font-size: 2em;
		background-color: #00DDDD;
		font-family:Helvetica, Arial, Sans;
		}
	.list {
		margin: 20px;
		margin-left: 40px;
	}
	   .header {
		font-size: 1.7em;
		background-color: #00DDDD;
		font-family:Helvetica, Arial, Sans;
	}
	.button {
		background-color: #DDDDDD;
		border-radius: 1em; 
		font-size:1.8em;
		width: 14em;
		height: 3em;
		}
	
</style>

</head>
<body>


<table class="headtable" width="100%" border="0">
<tbody>
<tr bgcolor="#00DDDD" >
<td valign="top" align="left"><img src="betatrcorner.png" height="150px"></td>
<td valign="top" align="center" class="header">Help page for Suspects' Wine Tracker</td>
<td valign="top" align="right"><a href="glass-of-wine.jpg"><img src="glass-of-wine.jpg" height="150px"></a></td>

</tbody>
</table>

<h2>How to enter and save changes</h2>
This new interface allows you to toggle selecting people on and off. When you are done, use the "SAVE" button to make the changes. The "REFRESH" button just reloads the page cleanly.
<h3>Steps:</h3>
<div class = "list">
<ol>
<li>Click/touch the names of the people who had a drink. This will update the figures on the right that track how many times people have imbibed.</li> 
<li>If you make a mistake, click/touch the button again to undo it.</li>
<li>Note the emphasised buttons on the right corresponding to each imbiber. One is coloured yellow to indicate the one with the lowest donation:imbibing ratio.</li> 
<li>Select as many buttons on the right as there are donors (only one donation per person - if someone donates twice you will need to save this change and then save another donation without clicking the imbiber button).</li>
<li>When done, click/touch the "SAVE" button.</li>
</ol>
</div>
<h3>Undo</h3>
If you make a mistake and have saved it, you can use the "Undo one change" button to remove entries one by on in the history until you have removed all the changes you made. Then you can start again.
<br><br>
<center><a href="winetrackerBeta.php"><button class="button" type=button>Go to Wine Tracker Beta</button></a></center>
<br><br><hr>

<a href="glass-of-wine.jpg"><img src="glass-of-wine.jpg" width="100%"></a>
<hr><br /><font size="6">Last Update
<?php
	date_default_timezone_set("Australia/Canberra");
	echo date("F d Y H:i:s", filectime("winetrackerhelpBeta.php"));
	echo "</font>";
?>
</body>
</html>