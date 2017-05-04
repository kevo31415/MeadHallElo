<html>
<head>

</head>
<body>
<div id="wrapper">
<?php
require 'common/header.php';

//intake variables
$leagueid = $_POST["leagueid"];

//get league name
require 'common/connectToDB.php';

$sql = "SELECT name FROM leagues where id = $leagueid";
$result = $conn->query($sql)->fetch_assoc();
$leagueName = $result["name"];

echo "<h2>$leagueName Rankings</h2>"
?>


<br><br>

<table>
 <?php
 
 //define and run query of players in this league
 $sql = "SELECT * FROM players WHERE league = $leagueid ORDER BY rating DESC";
 $result = $conn->query($sql);
 
 //echo var_dump($result->fetch_assoc());
 
 if($result->num_rows > 0) {
	 echo "<tr><th>Player Name</th><th>Rating</th><th>Wins</th><th>Losses</th><th>Played</th></tr>"; //table header
	 //output the data of each row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td><a>" . $row["name"] . "</a></td>"; //player name and link to player page (GET)
		 echo "<td>" . $row["rating"] . "</td>"; //player rating
		 echo "<td>" . $row["wins"] . "</td>"; //player wins
		 echo "<td>" . $row["losses"] . "</td>"; //player losses
		 echo "<td>" . $row["played"] . "</td>"; //player matches played
		 echo "</tr>";
	 }
 } else {
	 echo "No players found for this league.";
 }
 
 $conn->close();

 
?>
</table>
<br><br>
<a href="selectLeagueView.php"><button>Go Back</button></a>
</div>
</body>

</html>