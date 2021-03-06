<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';


//intake variable and reset variables
$playerid = $_POST["id"];
$wld = ""; //this variable makes the W and L different colors in the match history table

//get values to populate infotable
require 'common/connectToDB.php';
$sql = "SELECT * FROM players where id = $playerid";
$result = $conn->query($sql)->fetch_assoc();
$conn->close();

$player = new Player($result);

$leagueName = leagueNameByid($player->league);
$playerDrawn = $player->played - $player->wins - $player->losses;

//win percentage
if ($player->played == 0) {
	$playerWinPct = 0;
} else {
	$playerWinPct = round($player->wins / $player->played * 100, 1);
}

?>

<table id="infoTable">
<tr><th class="left"><?php echo $player->name; ?></th><th class="left">Elo Rating</th><th class="right">Win Percentage</th></tr>
<tr><td class="left"><?php echo $leagueName; ?></td><td class="left" style="font-size: 2em;"><?php echo $player->rating; ?></td><td class="right" style="font-size: 2em;"><?php echo $playerWinPct; ?>%</td></tr>
<tr><td></td><td></td><td class="right"><?php echo $player->wins; ?><span class="winMatch">W</span> | <?php echo $player->losses; ?><span class="loseMatch">L</span> | <?php echo $playerDrawn; ?>D</td></tr>
</table>

<br><br>

<h3>Match History (last 90 days)</h3>
<table>

<?php

//create and check connection
require 'common/connectToDB.php';

//create array of this league's players with names indexed by player ID (so we don't need to join tables)
$sql = "SELECT id, name FROM players WHERE league = ". $player->league;

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()){
	$playerNames[$row["id"]] = $row["name"];
}

//query player match results, last 90 days
$sql = "SELECT player, opponent, outcome, newrating, delta, DATE_FORMAT(match_time, '%m-%d-%Y') AS 'matchdate' FROM matches WHERE player = $playerid AND match_time BETWEEN (NOW() - INTERVAL 90 DAY) AND NOW() ORDER BY match_time DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	//draw table header
	echo "<tr><th>Result</th><th>Opponent</th><th>Date</th><th>Rating</th><th>Change</th></tr>";
	
	//output data for each row
	while($row = $result->fetch_assoc()) {
		
		//determine color of result and ranking change values
		switch ($row["outcome"]){ 
			case 0:
				$wld = "<span class=\"loseMatch\">L</span>";
				break;
				
			case 1:
				$wld = "<span class=\"winMatch\">W</span>";
				break;
				
			case 2:
				$wld = "D";
				break;
		}
		
		echo "<tr>"; // initiate row
		echo "<td>" . $wld . "</td>"; // Result column
		
		$n = $row["opponent"];
		echo "<td>" . $playerNames["$n"] . "</td>"; // opponent column
		echo "<td>" . $row["matchdate"] . "</td>"; // match date column
		echo "<td>" . $row["newrating"] . "</td>"; // ranking column
		
		if ($row["delta"] < 0) { //ranking change column
			echo "<td><span class=\"loseMatch\">" . $row["delta"] . "</span></td>";
		} elseif ($row["delta"] > 0) {
			echo "<td><span class=\"winMatch\">+" . $row["delta"] . "</span></td>";
		} else {
			echo "<td>" . 0 . "</td>";
		}
		
		echo "</tr>"; //end row
		}
	}
 else {
	echo "<th>No matches found for this player.</th>";
}
?>

</table>

<form name="navigate" method="post" action="viewLeague.php">

<button class="other" type="submit" name="leagueid" value=<?php echo $player->league; ?>>Go Back</button>

</form>


</div>
</body>

</html>