<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

//intake variable
$playerid = 2;

//get values to populate infotable



?>

<table id="infoTable">
<tr><th>Melvin Lee</th><th>Elo Rating</th><th>Win Percentage</th></tr>
<tr><td>Netrunner</td><td style="font-size: 2em;">1607</td><td style="font-size: 2em;">66%</td></tr>
<tr><td></td><td></td><td>2<span class="winMatch">W</span> | 1<span class="loseMatch">L</span> | 1D</td></tr>
</table>

<br>

<table id="matchHistory" >
<tr><th>Result</th><th>Opponent</th><th>Date</th><th>Ranking</th><th>Change</th></tr>

<?php

//create and check connection
require 'common/connectToDB.php';

//query player match results, last 90 days
$sql = "SELECT * FROM matches WHERE player = $playerid ";

?>

<tr><td><span class="winMatch">W</span></td><td>Abram Jopp</td><td>11-03-2017</td><td>1607</td><td><span class="winMatch">+2</span</td></tr>
<tr><td><span class="loseMatch">L</span></td><td>Abram Jopp</td><td>11-02-2017</td><td>1605</td><td><span class="loseMatch">-5</span></td></tr>
<tr><td>D</td><td>Kevin Huang</td><td>11-02-2017</td><td>1610</td><td>-1</td></tr>
<tr><td><span class="winMatch">W</span></td><td>Chris Mayfield</td><td>11-02-2017</td><td>1611</td><td><span class="winMatch">+3</span></td></tr>
</table>
</div>
</body>

</html>